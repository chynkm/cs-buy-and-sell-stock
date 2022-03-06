<?php include 'header.php';?>
<p class="lead">
We have successfully processed the uploaded stocks file.<br/>
Please use the below form to retrieve the numbers.
</p>

<form id="stock_info_form">
    <div class="row mb-2">
        <div class="col-sm-4">
            <label for="stock">Select a stock</label>
            <select class="form-control" id="stock">
                <?php foreach ($stocks as $stock): ?>
                <option value="<?php echo $stock; ?>"><?php echo $stock; ?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="start_datepicker">Start date</label>
                <input type="text" class="form-control" id="start_datepicker" placeholder="Enter a start date">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="end_datepicker">End date</label>
                <input type="text" class="form-control" id="end_datepicker" placeholder="Enter an end date">
            </div>
        </div>
    </div>

    <button type="button" id="stock_info" data-route="/stock-info" class="btn btn-primary">Find best profit</button>
</form>

<div class="table-responsive col-4 mt-4" id="stock_info_div">
</div>

<?php include 'footer.php';?>
