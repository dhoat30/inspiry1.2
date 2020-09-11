window.onload=function(){
    //fabric calculator
 let fabricType = document.getElementById('fabric-type'); 
 let fabricWidth = document.getElementById('fabric-width'); 
 let trackLength = document.getElementById('track-length');
 let pattern = document.getElementById('pattern'); 
 let patternInputHorizontal = document.getElementById('pattern-value-hr'); 
 let patternInputVertical = document.getElementById('pattern-value-vr'); 
 let formHiddenFields = document.querySelector('.form-hidden-field'); 
 
 let calcDataField = document.getElementById('calculated-data'); 
 let fButton = document.getElementById('f-button'); 
 
 let calForm = document.getElementById('cal-form')
 calForm.addEventListener('submit', (e)=>{
      e.preventDefault(); 
 
    console.log(fabricWidth.value)
     fabricWidth = parseFloat(fabricWidth.value); 
     trackLength = parseFloat(trackLength.value); 
    console.log("after parse " + fabricWidth); 
     
 
     let calcData; 
     
     if(fabricType.value == 'inverted' || fabricType.value == 'pencil'){ 
         let a = trackLength * 2; 
         calcData = a/fabricWidth; 
     }
     else { 
         calcData = 20; 
     }
 
     if(pattern.value == 'yes'){ 
         console.log(pattern.value); 
         
     }
 
     
 
     calcDataField.innerHTML = calcData;
     calcData = 0 ; 
     console.log('worked')
 
 
 })
 
   }