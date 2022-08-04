<?php
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
        header('Content-Disposition: attachment; filename=assinatura.html');
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
                    <div class="form-group">
                        <label for="Company">Company</label>
                        <select name="company" id="company">
                            <option value="etse">ETSE</option>
                            <option value="roh">ROH</option>
                            <option value="gascentre">Gas Centre</option>
                            <option value="bbqshop">BBQ Shop</option>
                            <option value="frtyfve">Frtyfve</option>
                            <option value="instrumental">Instrumental</option>
							<option value="njpc">Not Just PC</option>
							<option value="agrominerals">Agro Minerals</option>
                        </select>
                        </div>
						<div class="form-group">
						<label for="Name">Name</label>
                        <input type="text" class="form-control" id="Name" name="Sender[name]" placeholder="Enter your name">
                    </div>
                    <div class="form-group">
                        <label for="Phone">Job Title</label>
                        <input type="phone" class="form-control" id="Title" name="Sender[title]" placeholder="Tech Support Engineer">
                    </div>
                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type="email" class="form-control" id="Email" name="Sender[email]" placeholder="you@notjustpcs.co.uk">
                    </div>
                    <div class="form-group">
                        <label for="Mobile">Mobile Phone</label>
                        <input type="phone" class="form-control" id="Mobile" name="Sender[mobile]" placeholder="+XX (XX) XXXXX-XXXX">
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
                <p class="text-muted credit">developed by <a href="http://www.lucasms.net/">Lucas Machado</a>.</p>
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
