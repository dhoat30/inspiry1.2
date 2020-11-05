let $ = jQuery; 
class GeoTradeSearch{ 
    constructor(){ 
        this.events(); 
    }

    events(){ 
        $(document).on('submit', '.trade-directory .geodir-listing-search', this.ajaxCall); 
    }

    ajaxCall(e){
        
        let url = 'http://localhost/inspiry/search/?geodir_search=1&stype=gd_place&s=+&snear=&spost_category%5B%5D=118&sregions_covered%5B%5D=Auckland&spackage_id=&sgeo_lat=&sgeo_lon=';
        //creat an xhr object 
        var xhr = new XMLHttpRequest();


        //send get request 
        xhr.open('GET', url, true); 

        xhr.onload = function(){ 
        }

        xhr.send(); 


    }
}

export default GeoTradeSearch; 