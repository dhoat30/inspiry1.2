let $ = jQuery;

class SingleDesignBoards {
    constructor() {
        this.events();
    }

    events() {
        $('.action-btn-container .share').on('click', () => {
            $('.action-btn-container .share-icons').show();
        })

        $('.action-btn-container .share-icons .fa-times').on('click', () => {
            $('.action-btn-container .share-icons').hide();
        })

        //single card share board
        $('.single-board .board-card .share-btn').on('click', this.showCardShareContainer.bind(this));

    }

    showCardShareContainer(e) {
        $(e.target).closest('.pin-options-container').siblings('.share-icon-container').show();
        $(e.target).closest('.pin-options-container').siblings('.share-icon-container').find('.close-icon').on('click', () => {
            $('.share-icon-container').hide();
        })
    }
}

export default SingleDesignBoards;