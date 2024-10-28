


window.addEventListener('DOMContentLoaded', function(){
      var myForms = document.querySelectorAll('.avaibookSearch');
      var pickers = [];

      var nextFriday = moment().day('friday');
      var nextSunday = nextFriday.clone().add(2,'days');

      myForms.forEach(function (elem) {
            
            initCalendar = elem.querySelector('.avaibookDatePicker.init');
            endCalendar = elem.querySelector('.avaibookDatePicker.end');

            if (initCalendar && endCalendar){

                  

                  initInput = elem.querySelector('.avaibookDateInputInit');
                  initInput.value = nextFriday.format('YYYY-MM-DD');



                  
                  endInput = elem.querySelector('.avaibookDateInputEnd');
                  endInput.value = nextSunday.format('YYYY-MM-DD');

                  

                  initCalendar.addEventListener('blur',function(){
                        
                        this.parentNode.parentNode.querySelector(".avaibookDatePicker.end").focus();
                  });

                  pickers.push (new Lightpick({
                                          field: initCalendar,
                                          secondField: endCalendar,
                                          repick: false,
                                          autoclose: true,
                                          minDays:2,
                                          startDate:nextFriday,
                                          endDate:nextSunday,
                                          minDate:moment(),
                                          selectForward:true,
                                          hoveringTooltip:false,
                                          repick: false,
					  dropdowns: { 
    						years: { 
        						min: moment().format('YYYY'), 
        						max: moment().add(5,'years').format('YYYY'), 
    							}, 
    						months: true, 
					  },
                                          onSelect: function(start, end){
                                                inputStartId = this._opts.field.id.replace("avaibookArriveDateCalendar","avaibookArriveDateInput");
                                                inputEndId = this._opts.field.id.replace("avaibookArriveDateCalendar","avaibookDepartDateInput");
                                                
                                                document.getElementById(inputStartId).value = start?start.format('YYYY-MM-DD'):'';
                                                document.getElementById(inputEndId).value = end?end.format('YYYY-MM-DD'):'';
                                          },
                                    })
                              );
                  
            }
           
      });


      
      
    
});


