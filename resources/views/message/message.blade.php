@if(isset($success))
<script type="text/javascript">
	var message = "{{$success}}";
	toastr.options.timeOut = 3000; // How long the toast will display without user interaction
	toastr.options.extendedTimeOut = 5000; // How long the toast will display after a user hovers over it
	toastr.info(message)
</script>
@endif

@if(isset($error))
<script type="text/javascript">
	var message = "{{$error}}";
	toastr.options.timeOut = 4000; // How long the toast will display without user interaction
	toastr.options.extendedTimeOut = 6000; // How long the toast will display after a user hovers over it
	toastr.error(message);
</script>
@endif

@if(session('success'))
<script type="text/javascript">
	var message = "{{session('success')}}";
	toastr.options.timeOut = 3000; // How long the toast will display without user interaction
	toastr.options.extendedTimeOut = 4000; // How long the toast will display after a user hovers over it
	toastr.info(message)
</script>
@endif

@if(session('error'))
<script type="text/javascript">
	var message = "{{session('error')}}";
	toastr.options.timeOut = 4000; // How long the toast will display without user interaction
	toastr.options.extendedTimeOut = 6000; // How long the toast will display after a user hovers over it
	toastr.error(message);
</script>
@endif