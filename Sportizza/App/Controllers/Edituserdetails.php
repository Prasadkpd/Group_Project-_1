<?php

namespace App\Controllers;

use Core\View;
use \App\Auth;
use App\Models\CustomerModel;
use App\Models\EditProfileModel;
use App\Flash;
use App\Models\SignupModel;

class Edituserdetails extends Authenticated
{
    // Start of Rendering Edit profile view
    public function EditProfileAction()
    {
        View::renderTemplate('editprofileView.html');
    }
    // End of Rendering Edit profile view

    //Start of updating user's password
    public function updatepasswordAction()
    {
        //Assigning the edited profile details to Model variable
        $Model = new EditProfileModel($_POST);

        //Authenticating the user
        $user = Auth::getUser();

        //If the password is successfully updated
        if ($Model->saveNewPassword($user)) {
            Flash::addMessage('Your password has been successfully updated.');
            $this->redirect('/Edituserdetails/EditProfile');
            exit;
        }
        // If the password update is failed
        else {
            Flash::addMessage('Entered old password is incorrect!', Flash::WARNING);
            $this->redirect('/Edituserdetails/EditProfile');
        }
    }
    //End of updating user's password

    //Start of updating user's username
    //Check the function name
    public function updatedetailsAction()
    {
        //Assigning the edited username to user variable
        $user = new EditProfileModel($_POST);
        //Obtaining old username from Auth via session
        $oldUsername = Auth::getUser()->username;

        //Checking whether the new username already exists and whether the new username==old username
        if (EditProfileModel::findByUsername($user->username) && $oldUsername != $user->username) {
            Flash::addMessage('Failed to update, username already exists', Flash::WARNING);
            $this->redirect('/Edituserdetails/EditProfile');
            exit;
        }
        //If the username is successfully updated, redirect
        if ($user->updateNewUserDetails($oldUsername)) {
            Flash::addMessage('Successfully updated details');
            $this->redirect('/Edituserdetails/EditProfile');
        }
        //If the username update is failed, redirect with errors
        else {
            Flash::addMessage('Failed to update details', Flash::WARNING);
            $this->redirect('/Edituserdetails/EditProfile');
        }
    }
    //End of updating user's username

    //Start of updating user's mobile
    //Rendering the OTP page after clicking edit mobile
    public function updatemobileAction()
    {
        //Saving the URL to be directed after OTP page in the session
        $_SESSION['direct_url'] = ('/Edituserdetails/directtoenternumber');
        //Obtaining the current user's mobile number from Auth via Session
        $current_user = Auth::getUser();
        //Send SMS to the user's old primary contact
        otp::sendSMS($current_user->primary_contact);
        //Redirect to OTP view
        $this->redirect('/otp');
    }
    //Rendering the new mobile number form 
    public function directtoenternumberAction()
    {
        //Rendering view to enter the new mobile number
        View::renderTemplate('EnterMobile.html');
    }
    //Rendering the new mobile number form with errors or the success action
    public function redirectotppageAction()
    {
        //Checking whether the new mobile number already exists and redirect with errors
        if (EditProfileModel::findByMobileNumber($_POST['mobile_number'])) {
            Flash::addMessage('Number is currently used for another account, 
            enter another number', Flash::WARNING);
            $this->redirect('/Edituserdetails/directtoenternumber');
        }
        //Check whether the new mobile number is valid

        //If new mobile number is not already exist,
        else {
            //Change the direct_url saved in session to this URL
            $_SESSION['direct_url'] = ('/Edituserdetails/updateMobileNumber');
            //update the session's user's mobile number with new mobile number
            $_SESSION['number'] = $_POST['mobile_number'];
            //Send an SMS to the new mobile number with Auth via session
            otp::sendSMS($_POST['mobile_number']);
            //Redirect to OTP view
            $this->redirect('/otp');
        }
    }
    //Updating the newly entered mobile number 
    public function updateMobileNumber()
    {
        //Obtaining the username from the Auth via session
        $username = Auth::getUser()->username;
        //If the user's mobile number is successfully updated in the database
        if (EditProfileModel::updateNewMobileNumber($username, $_SESSION['number'])) {
            //Display the success message and redirect
            Flash::addMessage('Mobile number is successfully updated');
            $this->redirect('/Edituserdetails/EditProfile');
        }
        //If the user's mobile number is failed to update in the database
        else {
            Flash::addMessage('Failed to update mobile number. Please try again', Flash::WARNING);
            $this->redirect('/Edituserdetails/EditProfile');
        }
    }
    //End of updating user's mobile 
}
