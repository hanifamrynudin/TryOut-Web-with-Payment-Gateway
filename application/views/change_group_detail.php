
<script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-mXgVk4scCbmG_a_L"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<div class="container">

   
<h3><?php echo $title;?></h3>
  


 <div class="row">
    <form id="payment-form" method="post" action="<?=site_url()?>/snap/finish">
      <input type="hidden" name="result_type" id="result-type" value=""></div>
      <input type="hidden" name="result_data" id="result-data" value=""></div>
<div class="col-md-8">
<br> 
<div class="login-panel panel panel-default">
       <div class="panel-body"> 
   
   
   
           <?php 
       if($this->session->flashdata('message')){
           echo $this->session->flashdata('message');	
       }
       ?>	

           
        <div class="form-group">	 
        <label> Email</label> 
        <input type="email" id="email" name="email" value="<?php echo $result['email'];?>" readonly=readonly class="form-control" placeholder="<?php echo $this->lang->line('email_address');?>" required autofocus>
        </div>
        
           
        <div class="form-group">
                <label> Nama</label> 
                <label for="inputEmail" class="sr-only"><?php echo $this->lang->line('first_name');?></label> 
                <input id="first_name" readonly=readonly type="text"  name="first_name"  class="form-control"  value="<?php echo $result['first_name'];?>"  placeholder="<?php echo $this->lang->line('first_name');?>"   autofocus>
        </div>
        <div class="form-group">	 
                <label for="inputEmail" class="sr-only"><?php echo $this->lang->line('last_name');?></label> 
                <input id="last_name" readonly=readonly type="text"   name="last_name"  class="form-control"  value="<?php echo $result['last_name'];?>"  placeholder="<?php echo $this->lang->line('last_name');?>"   autofocus>
        </div>  

           <div class="form-group">	 
                   <label>Pembelian</label> 
                   <input id="kelas" readonly=readonly type="text" required  name="group_name"  class="form-control"  value="<?php echo $group['group_name'];?>" > 
           </div>
            
        
<div class="form-group">	 
                   <label for="inputEmail"  ><?php echo $this->lang->line('description');?></label> 
                   <input readonly=readonly name="description"  class="form-control"  value=" <?php echo $group['description'];?>">   </input>
           </div>
            
           <div class="form-group">	 
                   <label for="inputEmail"  ><?php echo $this->lang->line('price');?></label> 
                   <input id="jmlbayar" readonly=readonly type="text" required  name="price"  class="form-control"    value="<?php echo $group['price'];?>"   > 
           </div>
           


        <button id="pay-button" class="btn btn-default" type="submit"><?php echo $this->lang->line('submit');?></button>

        </div>
        </div>
     </form>
</div>






</div>

<script type="text/javascript">
  
    $('#pay-button').click(function (event) {
      event.preventDefault();
      $(this).attr("disabled", "disabled");
    
    var email = $("#email").val();
    var first_name = $("#first_name").val();
    var last_name = $("#last_name").val();
    var kelas = $("#kelas").val();
    var jmlbayar = $("#jmlbayar").val();

    $.ajax({
      type : 'POST',
      url: '<?=site_url()?>/snap/token',
      data : {email:email,first_name:first_name,last_name:last_name,kelas:kelas,jmlbayar:jmlbayar},
      cache: false,

      success: function(data) {
        //location = data;

        console.log('token = '+data);
        
        var resultType = document.getElementById('result-type');
        var resultData = document.getElementById('result-data');

        function changeResult(type,data){
          $("#result-type").val(type);
          $("#result-data").val(JSON.stringify(data));
          //resultType.innerHTML = type;
          //resultData.innerHTML = JSON.stringify(data);
        }

        snap.pay(data, {
          
          onSuccess: function(result){
            changeResult('success', result);
            console.log(result.status_message);
            console.log(result);
            $("#payment-form").submit();
          },
          onPending: function(result){
            changeResult('pending', result);
            console.log(result.status_message);
            $("#payment-form").submit();
          },
          onError: function(result){
            changeResult('error', result);
            console.log(result.status_message);
            $("#payment-form").submit();
          }
        });
      }
    });
  });

  </script>
