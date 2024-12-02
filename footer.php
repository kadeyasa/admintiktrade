<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script type="text/javascript">
	$('.add-setting').click(function(){
       	$('#modal-membership').modal('show');
    });
	function updatemember(id){
		$('#modal-edit').modal('show');
		$.ajax({
  				method: "POST",
  				url: "get-data-member.php",
  				data: { id: id}
			})
  			.done(function( msg ) {
            	var jsonObject = JSON.parse(msg);
            	$('#username').val(jsonObject.data.username);
            	$('#email').val(jsonObject.data.email);
            	$('#iddata').val(id);
            	$('#user_star').val(jsonObject.data.user_star);
            	$('#date_sub').val(jsonObject.data.date_subscribe);
            	$('#status').html('');
            	if(jsonObject.data.status==1){
                	$('#status').append('<option value="1">Active</option>');
                	$('#status').append('<option value="0">UnActive</option>');
                }else{
                	$('#status').append('<option value="0">UnActive</option>');
                	$('#status').append('<option value="1">Active</option>');
                }
            	if(jsonObject.data.member_type==1){
					$('#member_type').html('');
                	$('#member_type').append('<option value="1">Pro Member</option>');
                	$('#member_type').append('<option value="0">Free Member</option>');
                }else{
					$('#member_type').html('');
                	$('#member_type').append('<option value="0">Free Member</option>');
                	$('#member_type').append('<option value="1">Pro Member</option>');
                }
				//$('#date_sub').val('date_sub);
            });
	}
	function deletemember(id){
		Swal.fire({
  			title: "Are you sure?",
  			text: "You won't be able to revert this!",
  			icon: "warning",
  			showCancelButton: true,
  			confirmButtonColor: "#3085d6",
  			cancelButtonColor: "#d33",
  			confirmButtonText: "Yes, delete it!"
		}).then((result) => {
  		if (result.isConfirmed) {
        	$.ajax({
  				method: "POST",
  				url: "delete-member.php",
  				data: { id: id}
			})
  			.done(function( msg ) {
            		var jsonObject = JSON.parse(msg);
            		if(jsonObject.error==0){
                    	Swal.fire({
                        	title: "Deleted!",
                        	text: jsonObject.message,
                        	icon: "success"
                    	});
                    	setTimeout(function() {
    						location.reload();
						}, 1000); 
                    }
            	});
  			}
		});
	}
	function showsearch(id){
		var v = $('#v'+id).val()
        if(v==0){
			$('#search'+id).show();
			$('#v'+id).val(1);
        }else{
        	$('#search'+id).hide();
			$('#v'+id).val(0);
        }
	}
	$("#search1").keyup(function(event) {
      // Number 13 is the "Enter" key on the keyboard
      if (event.keyCode === 13) {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the form submission
        $("#myForm").submit();
      }
    });

	$("#search2").keyup(function(event) {
      // Number 13 is the "Enter" key on the keyboard
      if (event.keyCode === 13) {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the form submission
        $("#myForm").submit();
      }
    });
	
	$("#search3").keyup(function(event) {
      // Number 13 is the "Enter" key on the keyboard
      if (event.keyCode === 13) {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the form submission
        $("#myForm").submit();
      }
    });

	$("#search4").keyup(function(event) {
      // Number 13 is the "Enter" key on the keyboard
      if (event.keyCode === 13) {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the form submission
        $("#myForm").submit();
      }
    });

	function approvewd(id){
    	Swal.fire({
  			title: "Are you sure?",
  			text: "Withdraw will process",
  			icon: "warning",
  			showCancelButton: true,
  			confirmButtonColor: "#3085d6",
  			cancelButtonColor: "#d33",
  			confirmButtonText: "Yes, process it!"
		}).then((result) => {
  		if (result.isConfirmed) {
        		var info = {
    					id: id,
    					status: '2'
				};
        		$.ajax({
   						type: "POST",
   						data: {info:info,action:'approvewithdraw',method:'post'},
   						url: "action.php",
   						success: function(msg){
                        	var jsonData = JSON.parse(msg);
                        	if(jsonData.error==1){
                        		var icon='error';
                            }else{
                            	var icon='info';
                            }
                        	Swal.fire({
  								title: "Good job!",
  								text: jsonData.message,
  								icon: "success"
							});
                        	setTimeout(function(){
   								window.location.reload(1);
							}, 1000);
                        }
				});
  			}
		});
    }
</script>
</html>