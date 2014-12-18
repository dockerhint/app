
@extends('site.layouts.default')

{{-- Content --}}
@section('content')
@section('breadcrumbs', Breadcrumbs::render('CreateDeployment'))

	<style type="text/css">
		/* hide the extra submit button generated by jsonform */
		#additionalCloudProviderFields [type="submit"]{display:none;}
	</style>

	<div class="page-header">
		<div class="row">
			<div class="col-md-9">
				<h4>{{isset($deployment->id)?'Edit':'Create'}}  Deployment:</h4>
			</div>
		</div>
	</div>

	{{-- Create/Edit cloud deployment Form --}}
	<form id="cloud_account_idCredntialsForm" class="form-horizontal" method="post" action="@if (isset($deployment->id)){{ URL::to('deployment/' . $deployment->id . '/edit') }}@endif" autocomplete="off">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->

		<!--
		<input type="hidden" name="docker_name" value="{{ $docker_name }}" />
		-->
		<!-- name -->
		<div class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
			<label class="col-md-2 control-label" for="docker_name">Docker Name</label>
			<div class="col-md-6">
				<input class="form-control" type="text" name="docker_name" id="docker_name" value="{{ $docker_name }}" required />
			</div>
		</div>
		
		<!-- cloud_account_id -->
		<div class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
			<label class="col-md-2 control-label" for="cloud_account_id">Cloud Account <font color="red"> * </font></label>
			<div class="col-md-6">
				<select class="form-control" name="cloudAccountId" id="cloudAccountId" required>
					@foreach ($cloud_account_ids as $key => $value)
						<option value="{{$value->id}}" data-cloud-provider="{{{$value->cloudProvider}}}" {{{ Input::old('cloudAccountId', isset($deployment->cloudAccountId) && ($deployment->cloudAccountId == $key) ? 'selected="selected"' : '') }}}>{{{$value->name}}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<!-- ./ cloud_account_id -->
		
		<!-- name -->
		<div class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
			<label class="col-md-2 control-label" for="name">Name <font color="red"> * </font></label>
			<div class="col-md-6">
				<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($deployment->name) ? $deployment->name : null) }}}" required />
			</div>
		</div>

		<!-- ./ username -->
		<div id="additionalCloudProviderFields">
			
		</div>	
		
		<div id="instanceImage">
			
		</div>	
		
		
		<!-- Form Actions -->
		<div class="form-group">
			<div class="col-md-offset-2 col-md-10">
				<a id="deploy_back_btn" href="{{ URL::to('/') }}" class="btn btn-default">Back</a>
				<button id="deploy_save_btn" type="submit" class="btn btn-primary">Deploy</button>
			</div>
		</div>
		<!-- ./ form actions -->
		
		<input type="hidden" id="js-imagelookup" name="imageLookup" value="{{ URL::to('deployment/images') }}" />
	
	</form>
@stop

@section('scripts')
<script  src="{{asset('bower_components/jsonform/deps/underscore.js')}}"></script>
<script  src="{{asset('bower_components/jsonform/lib/jsonform.js')}}"></script>
<script src="{{asset('assets/js/loadlib.js')}}"></script>
<script type="text/javascript">
	
	(function($){
		'use strict';
		var PROVIDERS = {{ json_encode($providers) }};
		var SAVED_PARAMETERS = {{ !empty($deployment -> parameters) ? $deployment -> parameters : 'null' }};
		$(function(){
			var $additionalCloudProviderFields = $('#additionalCloudProviderFields');
			var $cloud_account_id = $('#cloudAccountId');
			$cloud_account_id.on('change', function(){
				var cloudProvider = $(this).find('option:selected').data('cloud-provider');
				console.log('cloudProvider', cloudProvider);
				var schema = PROVIDERS[cloudProvider] || {}, values = {};
				for(var parameterKey in SAVED_PARAMETERS) {
					if(!SAVED_PARAMETERS.hasOwnProperty(parameterKey) ){
						continue;
					}
					values['parameters['+parameterKey+']'] = SAVED_PARAMETERS[parameterKey];
				}
				$additionalCloudProviderFields.empty().jsonForm({
			        schema: schema,
			        params: {
			        	fieldHtmlClass: 'form-control'
			        },
			        value: values
		      	});
		      	// Patch in bs3 classes
		      	$additionalCloudProviderFields
		      		.find('.control-group')
		      		.removeClass('control-group')
		      		.addClass('form-group');
		      	$additionalCloudProviderFields
		      		.find('.control-label')
		      		.addClass('col-md-2');
		      	$additionalCloudProviderFields
		      		.find('.controls')
		      		.removeClass('controls')
		      		.addClass('col-md-6');
			}).trigger('change');
			
			
			
			var $cloud_account_idCredntialsForm = $('#cloud_account_idCredntialsForm');
			$cloud_account_idCredntialsForm.on('submit', function(e){
				
			});
			
		});
		
		
	})(jQuery);
	
	
</script>
@stop
