<?php require_once('config.php'); ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Datepicker CSS -->
    <link href="css/bootstrap-datepicker.min.css" rel="stylesheet" />

    <title>Timesheet</title>
  </head>
  <body>
    <div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Timesheet</a>
        </div>
    </nav>
        <!-- Content here -->
        <h5>Report Time</h5>
        <div class="card">
            <div class="card-header">
                <h6>Timesheet</h6>
            </div>
            <div class="row">
                <div class="col-md-12 p-5">
                    <form method="post" action="send_report.php">
                        <div class="mb-3">
                            <label for="report_date" class="form-label">Report Date :</label>
                            <input type="text" class="form-control" name="report_date" id="report_date" readonly placeholder="mm/dd/yyyy" value="<?php echo date('m/d/Y'); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email_to" class="form-label">To: [Email address]</label>
                            <input type="email" class="form-control" name="email_to" id="email_to" placeholder="name@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject :</label>
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label"><strong>Add Tasks</strong> <a id="add_task" class="btn btn-primary">+</a></a></label>
                            <table id="tasks_table" class="table">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Task</th>
                                        <th>Description</th>
                                        <th>Time Worked</th>
                                        <th>Billable</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="clonetr">
                                        <td width="200">
                                            <select class="form-select" name="projects[]" id="projects_1">
                                                <option value="">Select Project</option>
                                                <?php
                                                    foreach($config['projects'] as $project)
                                                    {
                                                        echo '<option value="' . $project . '">' . $project . '</option>';
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                        <td width="400">
                                            <input type="text" class="form-control" name="task[]" id="task_1" placeholder="Enter Task">
                                        </td>
                                        <td width="400">
                                            <textarea name="description[]" class="form-control" id="description_1" rows="3" placeholder="Enter Description"></textarea>
                                        </td>
                                        <td width="200">
                                            <select class="form-select" name="hours[]" id="hours_1" style="display:inline;  width:40%">
                                                <option value="">Hour</option>
                                                <?php
                                                    for ($i = $config['hours']['from']; $i <= $config['hours']['to']; $i++)
                                                    {
                                                        $j = ($i < 10) ? '0'.$i : $i;
                                                        echo '<option value="' . $i . '">' . $j . '</option>';
                                                    }
                                                ?>
                                            </select>
                                            :
                                            <select class="form-select" name="minutes[]" id="minutes_1" style="display:inline; width:40%">
                                            <option value="">Min.</option>
                                                <?php
                                                    foreach($config['minutes']as $min)
                                                    {
                                                        $min = ($min < 10) ? '0'.$min : $min;
                                                        echo '<option value="' . $min . '">' . $min . '</option>';
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                        <td width="50"><input type="checkbox" name="billable[]" /></td>
                                        <td width="50"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Send Report</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer">
                With Best Wishes From: Taranjeet Singh Chhabra
            </div>
        </div>
    </div>
    

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
        $('document').ready(function() {
            $('#report_date').datepicker();
            $('#add_task').click(function(e){
                var tr = $('#clonetr').clone();
                tr.attr('id', "row_"+($('#tasks_table tbody tr').length + 1))
                $('#tasks_table tbody').append(tr);
                $('#tasks_table tbody tr').each(function(index){
                    $(this).find('select[name="projects[]"]').attr('id', 'projects_'+(index+1));
                    $(this).find('input[name="task[]"]').attr('id', 'task_'+(index+1));
                    $(this).find('textarea[name="description[]"]').attr('id', 'description_'+(index+1));
                    $(this).find('select[name="hours[]"]').attr('id', 'hours_'+(index+1));
                    $(this).find('select[name="minutes[]"]').attr('id', 'minutes_'+(index+1));
                    $(this).find('input[name="billable[]"]').attr('id', 'billable_'+(index+1));
                    if($(this).attr('id') != 'clonetr') {
                        $(this).find('td:last').html("<a id='delete_task_"+(index+1)+"' class='btn btn-danger delete_task'>-</a>");
                    }
                });
                $('.delete_task').click(function(e){
                    $(this).parent().parent().remove();
                    $('#tasks_table tbody tr').each(function(index){
                        $(this).find('select[name="projects[]"]').attr('id', 'projects_'+(index+1));
                        $(this).find('input[name="task[]"]').attr('id', 'task_'+(index+1));
                        $(this).find('textarea[name="description[]"]').attr('id', 'description_'+(index+1));
                        $(this).find('select[name="hours[]"]').attr('id', 'hours_'+(index+1));
                        $(this).find('select[name="minutes[]"]').attr('id', 'minutes_'+(index+1));
                        $(this).find('input[name="billable[]"]').attr('id', 'billable_'+(index+1));
                    });
                });
            });
        });
    </script>
  </body>
</html>