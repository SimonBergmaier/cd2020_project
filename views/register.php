<?php

use Blog\AuthenticationManager;
use Blog\Util;

if (AuthenticationManager::isAuthenticated()) {
    Util::redirect("index.php");
}
$userName = isset($_REQUEST['userName']) ? $_REQUEST['userName'] : null;
?>

<?php
require_once('views/partials/header.php');
?>


    <div class="page-header">
        <h2>Register</h2>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Please fill out the form below:
        </div>
        <div class="panel-body">

            <form class="form-horizontal" method="post" action="<?php echo Util::action(Blog\Controller::ACTION_REGISTER, array('view' => $view)); ?>">
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">User name:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="inputName" name="<?php print Blog\Controller::USER_NAME; ?>" placeholder="Please enter a Username" value="<?php echo htmlentities($userName); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" id="inputPassword" name="<?php print Blog\Controller::USER_PASSWORD; ?>" placeholder="Please enter a Password" required>
                    </div>
                </div>
                <div class="form-group has-feedback has-feedback-right" id="validate">
                    <label for="inputPasswordRep" class="col-sm-2 control-label">Repeat Password</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" id="inputPasswordRep" name="<?php print Blog\Controller::USER_PASSWORDREP; ?>" placeholder="Please repeat the Password" required>
                        <i class="form-control-feedback glyphicon" id="valid"></i>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-6">
                        <button type="submit" class="btn btn-default">Register</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
<script type="text/javascript">
    function hasClass(el, className) {
        if (el.classList)
            return el.classList.contains(className)
        else
            return !!el.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'))
    }

    function addClass(el, className) {
        if (el.classList)
            el.classList.add(className)
        else if (!hasClass(el, className)) el.className += " " + className
    }

    function removeClass(el, className) {
        if (el.classList)
            el.classList.remove(className)
        else if (hasClass(el, className)) {
            var reg = new RegExp('(\\s|^)' + className + '(\\s|$)')
            el.className=el.className.replace(reg, ' ')
        }
    }

    document.getElementById("inputPasswordRep").onkeyup = function() {
        var valid = document.getElementById("valid");
        var validate = document.getElementById("validate");

        if(document.getElementById("inputPasswordRep").value === "") {
            removeClass(valid, "glyphicon-remove");
            removeClass(valid, "glyphicon-ok");
            removeClass(validate, "has-error");
            removeClass(validate, "has-success");
            return;
        }

        if(document.getElementById("inputPasswordRep").value === document.getElementById("inputPassword").value) {
            removeClass(valid, "glyphicon-remove");
            removeClass(validate, "has-error");
            addClass(valid, "glyphicon-ok");
            addClass(validate, "has-success");
        } else {
            addClass(valid, "glyphicon-remove");
            addClass(validate, "has-error");
            removeClass(valid, "glyphicon-ok");
            removeClass(validate, "has-success");
        }
    };
</script>

<?php
require_once('views/partials/footer.php');