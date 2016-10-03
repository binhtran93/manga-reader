<!DOCTYPE html>
<html lang="en" data-ng-app="AdminApp">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Page</title>

    <link href="css/app.min.css" rel="stylesheet">
    
    <script src="js/vendor.min.js"></script>

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <div data-ng-include="'header.html'"></div>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <a href="admin#/manga"><i class="fa fa-dashboard"></i> Dashboard</a>
                            </li>
                        </ol>
                    </div>
                </div>
                
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    
    <script src="js/app.min.js"></script>
</body>

</html>
