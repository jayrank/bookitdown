/*!
Rescalendar.js - https://cesarchas.es/rescalendar
Licensed under the MIT license - http://opensource.org/licenses/MIT

Copyright (c) 2019 César Chas
*/



;(function($) {

    $.fn.rescalendar = function( options ) {

        function alert_error( error_message ){

            return [
                '<div class="error_wrapper">',

                      '<div class="thumbnail_image vertical-center">',
                      
                        '<p>',
                            '<span class="error">',
                                error_message,
                            '</span>',
                        '</p>',
                      '</div>',

                    '</div>'
            ].join('');
        
        }

        function set_template( targetObj, settings ){

            var template = '',
                id = targetObj.attr('id') || '';

            if( id == '' || settings.dataKeyValues.length == 0 ){

                targetObj.html( alert_error( settings.lang.init_error + ': No id or dataKeyValues' ) );
                return false;
            
            }

            if( settings.refDate.length != 10 ){

                targetObj.html( alert_error( settings.lang.no_ref_date ) );
                return false;
                
            }


            template = settings.template_html( targetObj, settings );

            targetObj.html( template );

            return true;

        };

        function dateInRange( date, startDate, endDate ){

            if( date == startDate || date == endDate ){
                return true;
            }

            var date1        = moment( startDate, settings.format ),
                date2        = moment( endDate, settings.format ),
                date_compare = moment( date, settings.format);

            return date_compare.isBetween( date1, date2, null, '[]' );

        }

        function dataInSet( data, name, date ){

            var obj_data = {};

            for( var i=0; i < data.length; i++){

                obj_data = data[i];

                if( 
                    name == obj_data.name &&
                    dateInRange( date, obj_data.startDate, obj_data.endDate )
                ){ 
                    
                    return obj_data;

                }

            } 

            return false;

        }

        function setData( targetObj, dataKeyValues, data ){

            var html          = '',
                dataKeyValues = settings.dataKeyValues,
                data          = settings.data,
                arr_dates     = [],
                name          = '',
                content       = '',
                hasEventClass = '',
                customClass   = '',
                classInSet    = false,
                obj_data      = {};

            targetObj.find('td.day_cell').each( function(index, value){

                arr_dates.push( $(this).attr('data-cellDate') );

            });

            for( var i=0; i<dataKeyValues.length; i++){

                content = '';
                date    = '';
                name    = dataKeyValues[i];

                html += '<tr class="dataRow">';
                html += '<td class="firstColumn">' + name + '</td>';
                
                for( var j=0; j < arr_dates.length; j++ ){

                    title    = '';
                    date     = arr_dates[j];
                    obj_data = dataInSet( data, name, date );
                    
                    if( typeof obj_data === 'object' ){
                        
                        if( obj_data.title ){ title = ' title="' + obj_data.title + '" '; }

                        content = '<a href="#" ' + title + '>&nbsp;</a>';
                        hasEventClass = 'hasEvent';
                        customClass = obj_data.customClass;

                    }else{

                        content       = ' ';
                        hasEventClass = '';
                        customClass   = '';
                    
                    }
                    
                    html += '<td data-date="' + date + '" data-name="' + name + '" class="data_cell ' + hasEventClass + ' ' + customClass + '">' + content + '</td>';
                }

                html += '</tr>';

            }

            targetObj.find('.rescalendar_data_rows').html( html );
        }

        function setDayCells( targetObj, refDate ){

            var format   = settings.format,
                f_inicio = moment( refDate, format ).subtract(settings.jumpSize, 'days'),
                f_fin    = moment( refDate, format ).add(settings.jumpSize, 'days'),
                today    = moment( ).startOf('day'),
                html            = '<td class="firstColumn"></td>',
                f_aux           = '',
                f_aux_format    = '',
                dia             = '',
                dia_semana      = '',
                num_dia_semana  = 0,
                mes             = '',
                clase_today     = '',
                clase_middleDay = '',
                clase_disabled  = '',
                middleDay       = targetObj.find('input.refDate').val();

            for( var i = 0; i< (settings.calSize + 1) ; i++){

                clase_disabled = ''; 

                f_aux        = moment( f_inicio ).add(i, 'days');
                f_aux_format = f_aux.format( format );
					
                dia        = f_aux.format('DD');
                mes        = f_aux.locale( settings.locale ).format('MMM').replace('.','');
                dia_semana = f_aux.locale( settings.locale ).format('ddd');

                f_aux_format == today.format( format ) ? clase_today     = 'today'         : clase_today = '';
                f_aux_format == middleDay              ? clase_middleDay = 'middleDay' : clase_middleDay = '';

                if( 
                    settings.disabledDays.indexOf(f_aux_format) > -1 ||
                    settings.disabledWeekDays.indexOf( num_dia_semana ) > -1
                ){
                    
                    clase_disabled = 'disabledDay';
                }
				
				var todayDate = new Date();
				var finalDate = todayDate.getFullYear() + '-' +((todayDate.getMonth()+1)<10 ? '0' : '') + (todayDate.getMonth()+1) +'-'+(todayDate.getDate()<10 ? '0' : '') + todayDate.getDate();
				
				var stylepointer = '';
				if(finalDate > f_aux_format){
					clase_disabled = 'disabledDay';
					stylepointer = 'style="pointer-events:none;"';
				}
				
				$("#monthNameForCal").val(mes);
				
                html += [
                    '<td class="day_cell ' + clase_today + ' ' + clase_middleDay + ' ' + clase_disabled + '" data-cellDate="' + f_aux_format + '" '+stylepointer+'>',
                        '<span class="dia_semana">' + dia_semana + '</span>',
                        '<span class="dia">' + dia + '</span>',
                        '<span class="mes">' + mes + '</span>',
                    '</td>'
                ].join('');

            }

            targetObj.find('.rescalendar_day_cells').html( html );

            addTdClickEvent( targetObj );

            setData( targetObj )

            
        }

        function addTdClickEvent(targetObj){

            var day_cell = targetObj.find('td.day_cell');

            day_cell.on('click', function(e){
            
                var cellDate = e.currentTarget.attributes['data-cellDate'].value;

                targetObj.find('input.refDate').val( cellDate );

                setDayCells( targetObj, moment(cellDate, settings.format) );
				checkAva();
            });

        }

        function change_day( targetObj, action, num_days ){

            var refDate = targetObj.find('input.refDate').val(),
                f_ref = '';

            if( action == 'subtract'){
                f_ref = moment( refDate, settings.format ).subtract(num_days, 'days');    
            }else{
                f_ref = moment( refDate, settings.format ).add(num_days, 'days');
            }
            
            targetObj.find('input.refDate').val( f_ref.format( settings.format ) );
				
            setDayCells( targetObj, f_ref );

        }

		function checkAva()
		{
			var demo_book_date = $('#demo_book_date').val();
			var splitdate = demo_book_date.split("-");
			
			var todayDate = new Date();
			var finalDate = todayDate.getFullYear() + '-' +((todayDate.getMonth()+1)<10 ? '0' : '') + (todayDate.getMonth()+1) +'-'+(todayDate.getDate()<10 ? '0' : '') + todayDate.getDate();
			
			var selecteDate = new Date(splitdate[0], splitdate[1] - 1, splitdate[2],'00','00','00');
			var selDate = selecteDate.getFullYear() + '-' +((selecteDate.getMonth()+1)<10 ? '0' : '') + (selecteDate.getMonth()+1) +'-'+(selecteDate.getDate()<10 ? '0' : '') + selecteDate.getDate();
			
			var month = new Array();
			month[0] = "January";
			month[1] = "February";
			month[2] = "March";
			month[3] = "April";
			month[4] = "May";
			month[5] = "June";
			month[6] = "July";
			month[7] = "August";
			month[8] = "September";
			month[9] = "October";
			month[10] = "November";
			month[11] = "December";
			
			var bookData = month[(selecteDate.getMonth())]+" "+(selecteDate.getDate()<10 ? '0' : '') + selecteDate.getDate();
			$(".bkdt").html(bookData);
			/* if(finalDate <= selDate)
			{ */
				var csrf = $("input[name=_token]").val();
				var userId = $("#userId").val();
				var serviceIds = [];
				var staffId = $("#staffId").val();

				$("input[name='itemPrId[]']").each( function() {
					var sId = $(this).val();
					serviceIds.push(sId);
				});
				
				$(".loader").show();
				$.ajax({
					type: "POST",
					url: WEBSITE_URL+"/getBookingSlot",
					data: {service_ids : serviceIds, userId : userId, staffId : staffId, book_date : demo_book_date, _token : csrf},
					dataType : 'json',
					success: function (response) {
						if(response.status == true) 
						{
							var timeData = response.availability;		
							
							var timeHtml = "";
							$(".user_availability").html("");
							
							if(timeData.length > 0) {
								$.each(timeData, function(index, times) {
									var slots = times;
									$.each(slots, function(ind, val) {
										timeHtml += '<a href="javascript:;" class="list-group-item d-flex justify-content-between align-items-center selTimeSlots" data-booktime="'+val.start+'">';
											timeHtml += '<h6 class="mb-0">'+val.start+' to '+val.end+'</h6><i class="fa fa-chevron-right"></i>';
										timeHtml += '</a>';
									});
								});	
								$(".user_availability").html(timeHtml);
							} else {		
								timeHtml += '<a href="javascript:;" class="list-group-item d-flex justify-content-between align-items-center">';
									timeHtml += '<h6 class="mb-0">No time slot found.</h6><i class="fa fa-chevron-right"></i>';
								timeHtml += '</a>';	
								$(".user_availability").html(timeHtml);
							}	
						}
					}
				});
			/* }
			else
			{ */
				/* $(".user_availability").html('');	
							
				$("#demo_event_datetime").val('');
				$("#demo_event_client_datetime").val('');
				
				$(".submitButton").hide(); */
			//}
		}

        // INITIALIZATION
        var settings = $.extend({
            id           : 'rescalendar',
            format       : 'YYYY-MM-DD',
            refDate      : moment().format( 'YYYY-MM-DD' ),
			refMonth     : moment().format( 'MMM' ),
            jumpSize     : 15,
            calSize      : 30,
            locale       : 'en',
            disabledDays : [],
            disabledWeekDays: [],
            dataKeyField: 'name',
            dataKeyValues: [],
            data: {},

            lang: {
                'init_error' : 'Error when initializing',
                'no_data_error': 'No data found',
                'no_ref_date'  : 'No refDate found',
                'today'   : 'Today'
            },

            template_html: function( targetObj, settings ){
                	
                var id      = targetObj.attr('id'),
                    refDate = settings.refDate,
					refMonth = settings.refMonth;

                return [

                    '<div class="rescalendar ' , id , '_wrapper">',

                        '<div class="rescalendar_controls">',

                            '<button type="button" class="move_to_last_month"> << </button>',
                            '<button type="button" class="move_to_yesterday"> < </button>',

							'<input type="text" id="monthNameForCal" readonly value="' + refMonth + '" style="border:none;"/>',
                            '<input class="refDate" type="hidden" name="demo_book_date" id="demo_book_date" readonly value="' + refDate + '" />',
                            
                            '<button type="button" class="move_to_tomorrow"> > </button>',
                            '<button type="button" class="move_to_next_month"> >> </button>',

                            '<br>',
                            '<button type="button" class="move_to_today"> ' + settings.lang.today + ' </button>',

                        '</div>',

                        '<table class="rescalendar_table">',
                            '<thead>',
                                '<tr class="rescalendar_day_cells"></tr>',
                            '</thead>',
                            '<tbody class="rescalendar_data_rows">',
                                
                            '</tbody>',
                        '</table>',
                        
                    '</div>',

                ].join('');

            }

        }, options);




        return this.each( function() {
            
            var targetObj = $(this);

            set_template( targetObj, settings);

            setDayCells( targetObj, settings.refDate );

            // Events
            var move_to_last_month = targetObj.find('.move_to_last_month'),
                move_to_yesterday  = targetObj.find('.move_to_yesterday'),
                move_to_tomorrow   = targetObj.find('.move_to_tomorrow'),
                move_to_next_month = targetObj.find('.move_to_next_month'),
                move_to_today      = targetObj.find('.move_to_today'),
                refDate            = targetObj.find('.refDate');

            move_to_last_month.on('click', function(e){
                
                change_day( targetObj, 'subtract', settings.jumpSize);
				checkAva();
            });

            move_to_yesterday.on('click', function(e){
                
                change_day( targetObj, 'subtract', 1);
				checkAva();
            });

            move_to_tomorrow.on('click', function(e){
                
                change_day( targetObj, 'add', 1);
				checkAva();
            });

            move_to_next_month.on('click', function(e){
                
                change_day( targetObj, 'add', settings.jumpSize);
				checkAva();
            });

            refDate.on('blur', function(e){
                
                var refDate = targetObj.find('input.refDate').val();
                setDayCells( targetObj, refDate );
				checkAva();
            });

            move_to_today.on('click', function(e){
                
                var today = moment().startOf('day').format( settings.format );
                targetObj.find('input.refDate').val( today );

                setDayCells( targetObj, today );
				checkAva();	
            });
			
            return this;

        });

    } // end rescalendar plugin


}(jQuery));