$( document ).ready(function() {
	var $body = $(document.body);

	$body.on('click', '.delete_user', function(e){
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_users');
		$.ajax({
			'url': 'users/index.php',
			'type': 'post',
			'data': ({'del': $this.attr('data-del')}),
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});
	
	$body.on('click', '.add_tasks', function(e){
		event.preventDefault();
		var $this = $(this);
		$('#form_add_tasks').css("display", "block");
	});
	
	$body.on('click', '.add_importances', function(e){
		event.preventDefault();
		var $this = $(this);
		$('#form_add_importances').css("display", "block");
	});	
	
	$body.on('click', '.action', function(e){
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		var side;
		
		if($this.attr('data-side')=='asc'){
			side = 'desc';
		}else{
			side = 'asc';
		}
		
		$('#list_tasks .action').each(function(){
			$(this).attr('data-side', 'asc');
		});
		
		$this.attr('data-side', side);
		
		
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': ({'sort': $this.attr('data-sort'), 'side': $this.attr('data-side')}),
			'success': function (msg) {
				$list.html(msg);
			}
		});
		
		
	});	
	
	$body.on('submit', '#form_add_importances', function (e) {
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_importances');
		data = $this.serializeArray();
		$.ajax({
			'url': 'importances/index.php',
			'type': 'post',
			'data': data,
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});
	
	$body.on('click', '.delete_importances', function(e){
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_importances');
		$.ajax({
			'url': 'importances/index.php',
			'type': 'post',
			'data': ({'del': $this.attr('data-del')}),
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});	
	
		
	$body.on('click', '.cencel_edit', function(e){
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': ({'search-empty': '1'}),
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});	
	
				
	$body.on('click', '.filterImportances', function(e){//фильтрация по важности
		event.preventDefault();
		var $this = $(this);
		if($this.attr('data-filter')=='Y'){
			data=({'filterImportances': $("#filterImportances select[name=filterImportances] option:selected").val()});
		}else{
			data=({'filterImportances': null});
		}
		var $list = $('#list_tasks');
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': data,
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});	
					
	$body.on('click', '.filterDateCreate', function(e){//фильтрация по важности
		event.preventDefault();
		var $this = $(this);
		if($this.attr('data-filter')=='Y'){
			data=({'filterDateCreate': $("#filterDateCreate input[name=filterDateCreate]").val()});
		}else{
			data=({'filterDateCreate': null});
		}
		var $list = $('#list_tasks');
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': data,
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});	
	
			
	
	$body.on('submit', '#form_add_tasks', function (e) {
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		data = $this.serializeArray();
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': data,
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});
		
	$body.on('submit', '#form_edit_tasks', function (e) {
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		data = $this.serializeArray();
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': data,
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});
	
	$body.on('click', '.delete_tasks', function(e){
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': ({'del': $this.attr('data-del')}),
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});	
	
	$body.on('click', '.edit_tasks', function(e){
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': ({'edit': $this.attr('data-id')}),
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});	
	
	
	$body.on('click', '.done_tasks', function(e){
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': ({'done': $this.attr('data-id')}),
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});	
	
	$body.on('click', '#filter input', function(e){
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		var side;
		
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': ({'filter': $this.attr('id')}),
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});	
	
	
	$body.on('click', '#parrent input', function(e){
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		var side;
		
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': ({'parrent': $this.attr('id')}),
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});	
	
	$body.on('submit', '#search form', function (e) {
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		data = $this.serializeArray();
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': data,
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});
	
	$body.on('click', '.toRight', function(e){
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': ({'toRight': 1}),
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});	
	
	$body.on('click', '.toLeft', function(e){
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': ({'toLeft': 1}),
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});	
	
	$body.on('click', '.page', function(e){
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': ({'page': $this.attr('data-page')}),
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});	
		
	$body.on('click', '.sort-parrent', function(e){
		event.preventDefault();
		var $this = $(this);
		var $list = $('#list_tasks');
		$.ajax({
			'url': 'tasks/index.php',
			'type': 'post',
			'data': ({'sort-parrent': 'Y'}),
			'success': function (msg) {
				$list.html(msg);
			}
		});
	});	
	
});