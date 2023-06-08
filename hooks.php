<?php

add_hook('AdminAreaFooterOutput', 1, function() {
    $html = <<<HTML
</li><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <li><a href="#" data-toggle="modal" data-target="#modalPasswordChange"><i class="fa-solid fa-key"></i> Reset Password</>
    </a></li>
</li>
HTML;

return '<script type="text/javascript">jQuery("#summary-login-as-owner").parent().parent().append("' . preg_replace("/\r|\n/", "", str_replace('"', '\"', $html)) . '")</script>';

});

add_hook('AdminAreaFooterOutput', 1, function($vars){
    $clientId = $_GET['userid'];
    if($vars['helplink'] === 'Clients:Users_Tab')
    {
        $clientId = 'N/A';
    }
    return <<<HTML
<div class="modal fade" id="modalPasswordChange" role="dialog" aria-labelledby="PasswordResetLabel" aria-hidden="true">
    <div class="modal-dialog">
     <form action="#" onsubmit="sendRequest()" id="passwordChangeForm">
        <div class="modal-content panel panel-primary">
           
                <div id="modalPasswordChangeHeading" class="modal-header panel-heading">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="GenerateInvoicesLabel">Change User's Password</h4>
                </div>
                <div id="modalPasswordChangeBody" class="modal-body panel-body">
                      <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" class="form-control" id="password" aria-describedby="passwordHelp" required>
                        <input type="hidden" id="userid" value="$clientId">
                      </div>
                      <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" aria-describedby="confirmPasswordHelp" required>
                      </div>
                      <div class="form-group form-check">
                        <input type="checkbox" name="sendToClient" class="form-check-input" id="sendToClient">
                        <label class="form-check-label" id="sendToClient" for="sendToClient">Send new password to client</label>
                      </div>
                      
                    
                </div>
                <div id="modalPasswordChangeFooter" class="modal-footer panel-footer">
                <button type="submit" id="PasswordReset-Send" class="btn btn-primary">Set password</button>
                <button type="button" id="PasswordReset-Cancel" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            
        </div>
        </form>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

HTML;

});

add_hook('AdminAreaFooterOutput', 1, function() {
    return <<<HTML
<script type="text/JavaScript">
$(document).ready(function () {
  // Listen to submit event on the <form> itself!
  $('#passwordChangeForm').submit(function (e) {

    // Prevent form submission which refreshes page
    e.preventDefault();
  });
});
    
    function sendRequest() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "addonmodules.php?module=PasswordChange&action=changePassword", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                const response = JSON.parse(xhr.responseText);
                toastr.options = {
                      "closeButton": true,
                      "progressBar": true,
                      "newestOnTop": false,
                      "positionClass": "toast-bottom-right",
                      "showDuration": "300",
                      "hideDuration": "1000",
                      "timeOut": "5000",
                      "extendedTimeOut": "1000",
                      "showEasing": "swing",
                      "hideEasing": "linear",
                      "showMethod": "fadeIn",
                      "hideMethod": "fadeOut"
                    }
                if(response.result === 'error'){
                    if(response.message === 'emptyFields'){
                        toastr.error('Required fields are empty');
                    }else if(response.message === 'idIsNotAnInt'){
                        toastr.error('User ID is not an integer');
                    }else if(response.message === 'notmatching'){
                        toastr.error('Passwords does not match');
                    }else{
                        toastr.error('Unknown error');
                    }
                }else{
                    if(response.message === 'sentToClient'){
                        toastr.success('Password has been changed and sent to client');
                    }else{
                        toastr.success('Password has been changed');
                    }
                    document.getElementById("password").value = '';
                    document.getElementById("confirmPassword").value = '';
                }
          }};
        
        let password1 = document.getElementById("password").value;
        let password2 = document.getElementById("confirmPassword").value;
        let sendToClient = document.getElementById('sendToClient').checked;
        let userId = document.getElementById('userid').value;

        xhr.send("userid="+userId+"&password="+password1+"&confirmPassword="+password2+"&sendToClient="+sendToClient);
    }
</script>
HTML;
});

add_hook('AdminAreaFooterOutput', 1, function() {
    return <<<HTML
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
HTML;
});

add_hook('AdminAreaFooterOutput', 1, function() {
   return <<<HTML
<script>element = document.getElementById('Menu-Addons-Password Change for WHMCS').parentElement.remove();</script>
HTML;
});
