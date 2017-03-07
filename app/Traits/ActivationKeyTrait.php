<?php

namespace App\Traits;
 
use App\Logic\Activation\ActivationRepository;
use App\Models\User;
use App\Models\ActivationKey;
use Illuminate\Support\Facades\Validator;
use App\Notifications\ActivationKeyCreatedNotification;
 
use App\Mails\ActivationKeyCreated;
 
trait ActivationKeyTrait
{
     
    public function queueActivationKeyNotification(User $user)
    {
        $this->createActivationKeyAndNotify($user);
    }
   
    public function createActivationKeyAndNotify(User $user)
    {
        //if user is already activated, then there is nothing to do
        if ($user->activated) { 
            return redirect()->route('home')
                ->with('message', 'This account is already activated')
                ->with('status', 'success');
        }
         
        // check to see if we already have an activation key for this user. If so, use it. If not, create one
        $activationKey = activationKey::where('user_id', $user->id)->first();
        if(empty($activationKey)){
             // Create new Activation key for this user/email
            $activationKey = new ActivationKey;
            $activationKey->user_id = $user->id;
            $activationKey->activation_key = str_random(64);
            $activationKey->save();
        }
 
        //send Activation Key notification
        // TODO: in the future, you may want to queue the mail since sending the mail can slow down the response
        $user->notify(new ActivationKeyCreatedNotification($activationKey));
         
    }
     
    public function processActivationKey(ActivationKey $activationKey){
        // get the user associated to this activation key
        $userToActivate = User::where('id', $activationKey->user_id)
            ->first();
         
        if (empty($userToActivate)) {
            return redirect()->route('home')
                ->with('message', 'We could not find a user with this activation key! Please register to get a valid key')
                ->with('status', 'success');
        }
 
        // set the user to activated
        $userToActivate->activated = true;
        $userToActivate->save();
 
        // delete the activation key
        $activationKey->delete();
    }
}
