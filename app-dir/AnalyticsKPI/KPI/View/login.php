<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Smartdate KPI - Summary</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/styles.css" />
        <script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
        
    </head>
    <body>

        <?php include("KPI/View/Elements/banner.php"); ?>


        <div class="box blue-box">
            <div class="content">
                <h3>Analytics KPI</h3>

                <form id="loginBox" action="LoginCheck" method="POST" >
                    <label>Login:</label> <input type="text" name="login" size="15" /><br/>
                    <label>Password:</label> <input type="password" name="pwd" size="15" /><br/><br/>
                    <input type="submit" value="Submit" /><br/>
                    <?php echo isset($feedback) ? $feedback : "" ?>
                </form>

            </div>
            <div class="bot"></div>
        </div>

    </body>
</html>
