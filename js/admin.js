$$ = jQuery;






function showRentalTypeOptions(formNumber){
    
    var selected = $$("#rentalType"+formNumber+" option:selected").val();

    $$(".typeBlk"+formNumber+"#"+selected+"Blk"+formNumber).show();
    $$(".typeBlk"+formNumber+":not(#"+selected+"Blk"+formNumber+")").hide();
}


var cpBackground = [];
var cpMain = [];
var cpText = [];

$$(function() {


    var urlParams = new URLSearchParams(window.location.search);

    var tab = urlParams.get("tab")?urlParams.get("tab"):1;

    var formNumber = tab;
    
    //rental type select
    
    //for (var formNumber=1;formNumber<4;formNumber++){
        showRentalTypeOptions(formNumber);
        $$("#rentalType"+formNumber).change(function(){
            var number = $$(this).attr("data-form-number");
            showRentalTypeOptions(number);    
        });
    //}



    //Background color selector
   
    //for (var formNumber=1;formNumber<4;formNumber++){



        cpBackground[formNumber] = ColorPicker(
            document.getElementById('backgroundSlide'+formNumber),
            document.getElementById('backgroundPicker'+formNumber),
            function(hex, hsv, rgb) {
                var theParent = $$(this.pickerElement).parents(".backgroundColorSelectorBlk");
                var theInput = theParent.find("input.backgroundColor");
                var theColour = theParent.find(".colorViewer");

                theInput.val(hex);
                theColour.css("background",hex);
                //$$("#backgroundColor"+formNumber).val(hex);
                //$$("#backgroundColorView"+formNumber).css("background",hex);
            }
        );
        
        if ($$("#backgroundColor"+formNumber).val()){
            cpBackground[formNumber].setHex($$("#backgroundColor"+formNumber).val());
        }
        

        //Main color selector
        cpMain[formNumber] = ColorPicker(
            document.getElementById('mainSlide'+formNumber),
            document.getElementById('mainPicker'+formNumber),
            function(hex, hsv, rgb) {
                var theParent = $$(this.pickerElement).parents(".mainColorSelectorBlk");
                var theInput = theParent.find("input.mainColor");
                var theColour = theParent.find(".colorViewer");

                theInput.val(hex);
                theColour.css("background",hex);
                //$$("#backgroundColor"+formNumber).val(hex);
                //$$("#backgroundColorView"+formNumber).css("background",hex);
            }
        );
        
        if ($$("#mainColor"+formNumber).val()){
            cpMain[formNumber].setHex($$("#mainColor"+formNumber).val());
        }


        //text color selector
        cpText[formNumber] = ColorPicker(
            document.getElementById('textSlide'+formNumber),
            document.getElementById('textPicker'+formNumber),
            function(hex, hsv, rgb) {
                var theParent = $$(this.pickerElement).parents(".textColorSelectorBlk");
                var theInput = theParent.find("input.textColor");
                var theColour = theParent.find(".colorViewer");

                theInput.val(hex);
                theColour.css("background",hex);
                //$$("#backgroundColor"+formNumber).val(hex);
                //$$("#backgroundColorView"+formNumber).css("background",hex);
            }
        );

        if ($$("#textColor"+formNumber).val()){
            cpText[formNumber].setHex($$("#textColor"+formNumber).val());
        }
        
    //}//end for

    
   

    

    


});


