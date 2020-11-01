var fabricWidthLabel = $('#fabric-width-label');
var fabricHeightLabel = $('#fabric-height-label');
//var units = $('input[name=units]:checked').val();
var winWidth = window.innerWidth;
var fabricModal = $('#FabricCalculatorModal').length;

$("#fabric-pattern-match").val($("#loadedPatternMatch").val());

$(document).ready(function () {

   // loadfabric();
   // UpdateUnits();
    CalculatefabricNeeded();
    Reset();
});


function loadfabric() {

    $.ajax({
        url: '/calculator/MeasurementSnippet/?viewName=' + units,
        type: 'GET',
        dataType: 'html'
    })
     .success(function (result) {
         $("#fabric-group").html(result);
         UpdatefabricId();
     });
}

function UpdateUnits() {
    $('input[name=units]').change(function () {
        units = $('input[name=units]:checked').val();
        changefabric();
    });
}

function changefabric() {

    $.ajax({
        url: '/calculator/MeasurementSnippet/?viewName=' + units,
        type: 'GET',
        dataType: 'html',
        success: function (result) {
            $('#fabric-group').empty().append(result);
            UpdatefabricId();
        },
        error: function () {
            //todo 
            alert('failed');
        }
    });
}


function fabricCount() {
    return $("#fabric-group div").length;
}

function UpdatefabricId() {
    var count = $("#fabric-group > div").length;
    var lastfabric = $("#fabric-group > div:last-child");
    var id = lastfabric.attr('data-fabric');
    var newId = id + count;
    $("#fabric-group > div:last-child").attr('data-fabric', newId);
}

function CalculatefabricNeeded(e) {
    $('#calculate-fabric-btn').on('click', function (e) {
        e.preventDefault();

        // reset errors
        $('.has-error').removeClass('has-error');

        var check = ValidateInputs();
        var check2 = ValidateFabric();
        var style = $('input[name=style]:checked').val();


        // validate curtain inputs
        if(style=='curtains') {
            if(check>=$('input[name=measure]').length&&check2>=1&&validateHeading()==true&&validateFullness()==true) {
                fabricCalc();
            }
            else {
                if(check<$('input[name=measure]').length) {
                    $('#error-message').text('Please enter measurements').addClass('message error');
                }
            }
        }

        // validate blind inputs
        else if(style=='blinds') {
            if(check>=$('input[name=measure]').length&&check2>=1) {
                fabricCalc();
            }
            else {
                if(check<$('input[name=measure]').length) {
                    $('#error-message').text('Please enter measurements').addClass('message error');
                }
            }
        }

        // calculate fabric
        function fabricCalc() {
                $('#error-message, #error-message-2').empty().removeClass('message error');

                var fabricObject=CreateJsonObject('centimetres');

                //Removes the default text
                $(".default-text").replaceWith($('.result-text').fadeIn());
                // scroll result into view
                $('html,body,#sl-modal').animate({
                    scrollTop: $(".result-text").offset().top
                });
                console.log('this is how much you need ' + result.TotalFabricRequired);

/*
                $.ajax({
                    url: '/calculator/CalculateFabric',
                    type: 'POST',
                    data: JSON.stringify(fabricObject),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function(result) {

                        if(result.TotalFabricRequired) {
                            $('actual').empty().append(result.TotalFabricRequired);
                        }

                        if(result.EstimatedPrice!==null&&result.EstimatedPrice!=="£0.00") {
                            $('totalprice').empty().append(result.EstimatedPrice);
                            $('#pricing-info').show();
                        }
                    },
                    error: function() {
                        alert('failed to talk to server');
                    }
                });
                */
            }

    });
}


function ValidateInputs() {

    var int = 0;
    $('input[name=measure]').each(function () {
        if ($.isNumeric($(this).val())) {
            int++;
            $(this).removeClass('has-error');
        }
        else {
            int--;
            $(this).addClass('has-error');
        }
    });

    return int;
};

function validateHeading() {


        var $this = $('#heading-type option:selected');

        if($this.val()=="") {
            $('#heading-type').addClass('has-error');
            $('#error-message-2').text('Please select Heading Type').addClass('message error');
            return false;
        }
        else {
            $('#heading-type').removeClass('has-error');
        }

        return true;

}

function validateFullness() {


        var $this=$('#fullness option:selected');

        if($this.val()=="") {
            $('#fullness').addClass('has-error');
            $('#error-message-2').text('Please select Fullness').addClass('message error');
            return false;
        }
        else {
            $('#fullnesss').removeClass('has-error');
        }

        return true;

}

function ValidateFabric() {

    var int = 0;
    if ($.isNumeric($('#fabric-width').val())) {
        int++;
        $('#fabric-width').removeClass('has-error');
    }
    else {
        int--;
        $('#fabric-width').addClass('has-error');
    }

    return int;

}

function Reset(e) {
    $('#reset-btn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.fabric').val('');
        $('#fabric-group').empty();
        $('actual').empty().append("0 fabric");
        $('approx').empty().append("0 fabric");
        loadfabric();
        //hides the price on reset
        $('#pricing-info').hide();
        return false;
    });
}

function CreateJsonObject() {  
    var window;

    
 if('centimetres'==="centimetres") {
        window=getWindowCentimetres();
    }
    
    return {
        "Units": 'centimetres',
        "Singleorpair": $('#curtainType option:selected').val(),
        "usage": $("input[name='style']:checked").val(),
        "SSp": $('#Ssp').val(),
        "Fullness": $('#fullness option:selected').val(),
        "RollDimensions": {
            "Width": ($('#fabric-width').val()),
            "Height": $('#fabric-height').val(),
            "PatternMatch": $("#fabric-pattern-match").val(),
            "PatternRepeat": $("#fabric-repeat").val()
        },
        "window": window,
    };
}

function getWindowCentimetres() {
    var object={ "Width": null,"Height": null };

    $('#fabric-group > div').each(function() {
        var width=$(this).find('.width').val();
        var height=$(this).find('.height').val();

        var widthCm=width/100;
        var heightCm=height/100;

        object.Width = widthCm; 
        object.Height = heightCm; 

        // save window measurements to cookie
        $.cookie("calcWidth",widthCm);
        $.cookie("calcHeight",heightCm);
        // update inches measurement
        $.cookie("calcWidthInches",(Math.floor(widthCm*39.370)));
        $.cookie("calcHeightInches",(Math.floor(heightCm*39.370)));

    });

    return object;
}
/*
function getWindowInches() {
    var object = { "Width": null, "Height": null };

    $('#fabric-group > div').each(function () {
        var width = $(this).find('.width').val();
        var height = $(this).find('.height').val();

        object.Width = width * 0.0254;
        object.Height = height * 0.0254;

        // save window measurements to cookie
        $.cookie("calcWidthInches",width);
        $.cookie("calcHeightInches",height);
        // update metre measurement
        $.cookie("calcWidth",((width/39.370).toFixed(2)));
        $.cookie("calcHeight",((height/39.370).toFixed(2)));

    });

    return object;
}
*/

// style selection 

    // curtains or blinds   
    $("input[name='style']:radio")
        .change(function() {
            $("#curtain-styles").toggle($(this).val()=="curtains");
            $("#blind-styles").toggle($(this).val()=="blinds");
    });

    // pattern repeat
    $('#fabric-pattern-match')
        .change(function(){
            $('.patternRpt').toggle($(this).val()!=""); // show/hide pattern rpt input
            if($(this).val()=="") {
                $('#fabric-repeat').val("0"); // reset pattern rpt value
            };
        });

    // headings and fullness
    $("#heading-type")
        .change(function() {

            $('.fullness').toggle($(this).val()!=""); // show/hide fullness
            $("#fullness").val(''); // reset select value to null

            // toggle fullness values for selected heading type
            $(".pencilpleat").toggle($(this).val()=="Pencil Pleat");
            $(".gatheredheading").toggle($(this).val()=="Gathered Heading");
            $(".triplepleat").toggle($(this).val()=="Triple Pleat");
            $(".gobletheading").toggle($(this).val()=="Goblet Heading");
            $(".eyelets").toggle($(this).val()=="Eyelets");
            $(".tabtops").toggle($(this).val()=="Tab Tops");
        
        });


window.onload = function() {

    const designBoardSinglePage = new DesignBoard(); 
   const designBoardSaveBtn = new DesignBoardSaveBtn();
   
   
let designBoardAjax = new DesignBoardAjax(); 
   
const tradeNav = new TradeNav();

//profile navbar


   let profileNavbar = {
       eventListener: function (){ 
        $('.profile-name-value').click(function(e){
            let user = document.querySelector('.profile-name-value').innerHTML;  
            console.log("click working");
            if(user.includes('LOGIN / REGISTER'))
            { 
                console.log('Log In'); 
            }
            else{ 
                e.preventDefault(); 
                $('.my-account-nav').slideToggle(200, function(){ 
                    $('.arrow-icon').toggleClass('fa-chevron-up');
                }); 
            }
            
    })
       }
   }

   profileNavbar.eventListener();
    

    
    
   

}


