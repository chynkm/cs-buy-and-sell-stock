<?php include 'header.php';?>
<p class="lead">Please select the CSV file and submit the form.</p>
<form action="upload" method="post" enctype="multipart/form-data">
    <div class="input-group mb-3">
        <input name="stock_file" type="file" class="form-control" id="input" accept=".csv">
    </div>
    <button type="submit" class="btn btn-primary">Upload CSV file</button>
</form>
<?php include 'footer.php';?>
