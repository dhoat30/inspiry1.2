let $ = jQuery; 

class SingleDesignBoards { 
    constructor(){
        this.events(); 
    }

    events(){
        $('.action-btn-container .share').on('click', ()=>{
            $('.action-btn-container .share-icons').show();
        })

        $('.action-btn-container .share-icons .fa-times').on('click', ()=>{
            $('.action-btn-container .share-icons').hide();
        })
    }
}

export default SingleDesignBoards; 