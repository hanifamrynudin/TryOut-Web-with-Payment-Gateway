 
 <div class="container">
  
   <div>
  <h3><?php echo $title;?></h3>
  <a href="<?php echo site_url('user/change_group_history');?>" class="btn btn-warning"  ><?php echo "History Belanja";?> </a>
   </div>
	<hr>
	  
	<div class="row">
	  
	 <?php 
	 $cc=0;
 $colorcode=array(
 'success',
 'warning',
 'info',
 'danger'
 );
 $logged_in=$this->session->userdata('logged_in');
	  
 $uid=$logged_in['uid'];
			 $query=$this->db->query("select * from savsoft_users where uid='$uid' ");
				 $user=$query->row_array();
				 $asgid=explode(',',$user['gid']);
			 
			 
			 
	 foreach($group_list as $k => $val){
	 
	?>
				<div class="col-lg-4">
	
	<div class="card mb-4">
				 <div class="card-header">
		<?php echo $val['group_name'];?> 
		
 
				</div>
				 <div class="card-body" style="height:250px;overflow-y:auto;">
		 <?php 
						   echo $val['description'];?>
						 
 </div>
  <div class="card-footer">						
			 <?php 
 
 
  
 if(!in_array($val['gid'],$asgid)){
	 ?>
 
 <a href="<?php echo site_url('user/change_group_detail/'.$val['gid']);?>" class="btn btn-success"  ><?php echo "Beli";?> </a>
  <?php 
  if($val['price']==0){
 echo "<span style='float:right;'>Free </span>";
 }else{
 echo "<span style='float:right;'>".('Rp').' '.$val['price'].' '.$this->config->item('')."</span> "; 
 }
 }else{
	 ?>
 <button type="button" class="btn btn-default"><i class="fa fa-check-circle"></i>	<?php echo $this->lang->line('subscribed');?></button>
	 <?php 
 }
 ?>
				  </div>
	 </div>
			   
			   
			   
			   
			   
		 </div>	   
 
 
					 
				 <!-- /item --> 
	   
	   
	   <?php 
	   if($cc >= 4){
	   $cc=0;
	   }else{
	   $cc+=1;
	   }
	   
	 }
	 ?>
   
 </div>
 
  
 
 
 
 </div>
 
 <script>
  
 </script>
 