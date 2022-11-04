<!DOCTYPE html>
<html lang="en">
	
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Rooming Managment</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.12.1/datatables.min.css"/>
 
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/datatables.min.js"></script>
    
<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css">

<script language="JavaScript" src="https://code.jquery.com/jquery-1.11.1.min.js" type="text/javascript"></script>
<script language="JavaScript" src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script language="JavaScript" src="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="../css/tableclient.css">
</head>
<body>



    <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-4">
						<h2> <b>Rooming Management</b></h2>
					</div>
					<div class="col-sm-8">						
					<a href="#" class="btn btn-primary" onclick="window.location.reload()"><i class="material-icons">&#xE863;</i> <span>Reload</span></a>
						<!-- <a href="#" class="btn btn-info"><i class="material-icons">&#xE24D;</i> <span>Download CSV</span></a> -->
					</div>
                </div>
            </div>
			<div class="table-filter">
				<div class="row">
				
                    <div class="col-sm-3">
						
					</div>
                    <div class="col-sm-12">

					<button  class="btn"  data-toggle="collapse" data-target="#addMenu" aria-expanded="false" aria-controls="addMenu">
					Add <i class="fa fa-chevron-up pull-right"></i> 
                     <i class="fa fa-chevron-down pull-right"></i> 
					</button>

					<button  class="btn"  data-toggle="collapse" data-target="#filter_option" aria-expanded="false" aria-controls="filter_option">
					<i class="fa fa-filter"></i> Filter
					</button>

					<div id="filter_option" class="collapse">
						<button type="button" onclick="filterTable()" class="btn btn-primary"><i class="fa fa-search"></i></button>
						
						<div class="filter-group">
							<label>Rooming</label>
							<select id="room_num_f" class="form-control">
							<option value="">Select..</option>
							</select>
                            
						</div>
                        <div  class="filter-group">
							<label>Date</label>
							<input id="date_f" type="date" class="form-control">
						</div>
                        <div  class="filter-group">
							<label>Query</label>
							<input  id="query_f" type="text" class="form-control">
						</div>
				        </div>
                    </div>
				
                </div>
			</div>
            <table id="datatable"  class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Numéro d'hébergement affecté</th>
						<th>Type d'hébergement </th>
						<th>Nom </th>						
                        <th>Prénom </th>						
						<th>Date de naissance</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
				<tfoot>
				<tr id="addMenu" class="collapse" style="background: #d5d2d2;">
				<td>
					<select id="room_id" style="width:100px;" class="form-control">
				<option value="">Slectionner..</option>
                     </select>
					</td>
					 <td><input id="room_type" type="text" placeholder="Type" class="form-control"></td>
					 <td><input id="firstname" type="text"  placeholder="First Name" class="form-control"></td>
					 <td><input id="lastname" type="text" placeholder="Last Name" class="form-control"></td>
					 <td><input id="date" type="date" class="form-control" lang="fr-CA" placeholder="jj/mm/aaaa" ></td>
					 <td><button class="btn btn-primary" onclick="lastPage()">Add</button></td>
                    </tr>
				</tfoot>
            </table>

        </div>
        <button id="confirm" class="btn btn-primary disabled">Confirm</button>
    </div>


    <script type="text/javascript" src="../js/Http.js"></script>
    <script type="text/javascript" src="../js/tableclient.js"></script>
</body>
</html>                                		                            