<?php
error_reporting(0);

if (!empty($_REQUEST['Sender'])):
    $sender = $_REQUEST['Sender'];
    $layout = file_get_contents('./' . $_REQUEST['company'] . '.html', FILE_USE_INCLUDE_PATH);

    foreach ($sender as $key => $value) {
        $key         = strtoupper($key);
        $start_if    = strpos($layout, '[[IF-' . $key . ']]');
        $end_if      = strpos($layout, '[[ENDIF-' . $key . ']]');
        $length      = strlen('[[ENDIF-' . $key . ']]');

        if (!empty($value)) {
            // Add the value at its proper location.
            $layout = str_replace('[[IF-' . $key . ']]', '', $layout);
            $layout = str_replace('[[ENDIF-' . $key . ']]', '', $layout);
            $layout = str_replace('[[' . $key . ']]', $value, $layout);
        } elseif (is_numeric($start_if)) {
            // Remove the placeholder and brackets if there is an if-statement but no value.
            $layout = str_replace(substr($layout, $start_if, $end_if - $start_if + $length), '', $layout);
        } else {
            // Remove the placeholder if there is no value.
            $layout = str_replace('[[' . $key . ']]', '', $layout);
        }
    }

    // Clean up any leftover placeholders. This is useful for booleans,
    // which are not submitted if left unchecked.
    $layout = preg_replace("/\[\[IF-(.*?)\]\]([\s\S]*?)\[\[ENDIF-(.*?)\]\]/u", "", $layout);

    if (!empty($_REQUEST['download'])) {
        header('Content-Description: File Transfer');
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename=signature.html');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    }

    echo $layout;
else: ?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Lucas Machado">

        <title>Not Just PCs - Signature Generator</title> 
		
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="//static.notjustpcs.co.uk/style.css?v=20220802">
      	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
      	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      	<link href="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    </head>

    <body>

        <!-- Wrap all page content here -->
        <div id="wrap">

            <!-- Fixed navbar -->
            <div class="navbar navbar-default navbar-fixed-top">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Signature Generator</a>
                    </div>
                </div>
            </div>

            <!-- Begin page content -->
            <div class="container">
                <div class="page-header">
                    <h1>Not Just PCs - Signature Generator</h1>
                </div>
                <form role="form" method="post" target="preview" id="form">
				
				<?php
				if (is_null($_GET['company'])) :
			?>
                    <div class="form-group">
                        <label for="Company">Company</label>
                        <select name="company" id="company">
							<option value="notjustpcs.co.uk" default >Not Just PCs</option>
                            <option value="etse.uk">ETSE</option>
                            <option value="riveroakshomes.co.uk">ROH</option>
                            <option value="thegascentre.co.uk">Gas Centre</option>
                            <option value="thebbqshop.co.uk">BBQ Shop</option>
                            <option value="frtyfve.com">Frtyfve</option>
                            <option value="weareinstrumental.com">Instrumental</option>
							<option value="agrominerals.earth">Agro Minerals</option>
							<option value="pangdean.com">Pangdean</option>
                        </select>
                        </div>
						
			<?php
				
			else : $company = $_GET['company'];
			endif
			?>
					<h3>Hint</h3>
                    <p>You can preview your company signature to see which fields apply to you. If there's already a landline in the preview, then changing the landline number won't have any effect.</p>

                    <div class="form-group">
						<label for="Name">Name</label>
                        <input type="text" class="form-control" id="Name" name="Sender[name]" placeholder="Bob Bobberson">
                    </div>
                    <div class="form-group">
                        <label for="Title">Job Title</label>
                        <input type="text" class="form-control" id="Title" name="Sender[title]" placeholder="Tech Support Engineer">
                    </div>
                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type="email" class="form-control" id="Email" name="Sender[email]" placeholder="bob@notjustpcs.co.uk">
                    </div>
                    <div class="form-group">
                        <label for="Mobile">Mobile Number</label>
                        <input type="tel" class="form-control" id="Mobile" name="Sender[mobile]" placeholder="+XX (XX) XXXXX-XXXX">
                    </div>
                    <div class="form-group">
                        <label for="Landline">Landline Number</label>
                        <input type="tel" class="form-control" id="Landline" name="Sender[landline]" placeholder="+XX (XX) XXXXX-XXXX">
                    </div>
                    <div class="form-group">
                        <label for="Address">Address</label>
                        <input type="address" class="form-control" id="Address" name="Sender[Address]" placeholder="18 Guildford Road, BN1 3LU">
                    </div>
                    <div class="form-group">
                        <label for="Instagram">Instagram</label>
                        <input type="url" class="form-control" id="Instagram" name="Sender[instagram]" placeholder="https://instagram.com/notjustpcs">
                    </div>
                    <div class="form-group">
                        <label for="LinkedIn">LinkedIn</label>
                        <input type="url" class="form-control" id="LinkedIn" name="Sender[LinkedIn]" placeholder="https://uk.linkedin.com/company/not-just-pcs">
                    </div>
                    <div class="form-group">
                        <label for="WorkingHours">Working Hours</label>
                        <input type="text" class="form-control" id="WorkingHours" name="Sender[WorkingHours]" placeholder="My working hours are Mon/Thurs/Fri 9am-5pm">
                    </div>
                    <button id="preview" type="submit" class="btn btn-default">Preview</button>
                    <button id="download" class="btn btn-default">Download</button>
                    <input type="hidden" name="download" id="will-download" value="">
                </form>
            </div>
            <div class="container">
                <iframe src="about:blank" name="preview" width="100%" height="200"></iframe>
            </div>

        </div>

        <div id="footer">
            <div class="container">
                <p class="text-muted credit">developed by <a href="http://www.lucasms.net/">Lucas Machado</a> - Fettled by <a target="_blank" href="https://notjustpcs.co.uk">Not Just PCs</a>.</p>
            </div>
        </div>


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        $( document ).ready(function() {
            $("#download").bind( "click", function() {
                $('#will-download').val('true');
                $('#form').removeAttr('target').submit();
            });

            $("#preview").bind( "click", function() {
                $('#will-download').val('');
                $('#form').attr('target','preview');
            });

        });
        </script>
    </body>
</html>
<?php endif;
