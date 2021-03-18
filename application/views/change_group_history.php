
<table class="table table-bordered table-striped">
        <thead>
                <tr>
                       <th>Order ID</th>
                       <th>Email</th>
                       <th>Pembelian</th>
                       <th>Gross Amount</th>
                       <th>Payment Type</th>
                       <th>Transaction Time </th>
                       <th>Bank</th>
                       <th>Virtual Account Number</th>
                       <th>Status</th>
                </tr>
        </thead>
        <tbody>
                <?php foreach($midtrans as $d){ ?>
                        <tr>
                                <td><?php echo $d->order_id;?></td>
                                <td><?php echo $d->email;?></td>
                                <td><?php echo $d->kelas;?></td>
                                <td><?php echo $d->gross_amount;?></td>
                                <td><?php echo $d->payment_type;?></td>
                                <td><?php echo $d->transaction_time;?></td>
                                <td><?php echo $d->bank;?></td>
                                <td><?php echo $d->va_number;?></td>
                                <td>
                                <?php
                                 if($d->status_code == "200"){
                                  ?>
                                  <label for="" class="badge bg-success">Success</label>
                                 <?php 
                                 }
                                ?>
                                <?php
                                 if($d->status_code == "201"){
                                  ?>
                                  <label for="" class="badge bg-warning">Pending</label>
                                 <?php 
                                 }
                                ?>
                                <?php
                                 if($d->status_code == "202"){
                                  ?>
                                  <label for="" class="badge bg-danger">Failed</label>
                                 <?php 
                                 }
                                ?>
                                </td>
                        </tr>
                <?php } ?>
        </tbody>
</table>