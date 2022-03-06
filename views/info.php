<h2>Stock result</h2>
<table class="table">
    <tbody>
        <tr>
            <td><strong>Buy date</strong></td>
            <td><?php echo $stockInfo['buyDate'] ?></td>
        </tr>
        <tr>
            <td><strong>Sell date</strong></td>
            <td><?php echo $stockInfo['sellDate'] ?></td>
        </tr>
        <tr>
            <td><strong>Profit of one share(INR)</strong></td>
            <td><?php echo $stockInfo['profit'] ?></td>
        </tr>
        <tr>
            <td><strong>Profit for 200 shares(INR)</strong></td>
            <td><?php echo $stockInfo['stockProfit'] ?></td>
        </tr>
        <tr>
            <td><strong>Mean</strong></td>
            <td><?php echo $stockInfo['mean'] ?></td>
        </tr>
            <td><strong>Standard deviation</strong></th>
            <td><?php echo $stockInfo['standardDeviation'] ?></td>
        </tr>
    </tbody>
</table>
