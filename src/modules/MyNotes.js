class MyNotes {
    constructor() {
        this.deleteButton = document.querySelector('.delete-note');
        this.events();
    }

    events = () => {
        this.deleteButton.addEventListener('click', this.deleteNote);
    }

    // Methods

    deleteNote = () => {
        fetch(`${themeData.root_url}/wp-json/wp/v2/note/96`, {
            method: 'DELETE',
            headers: {
                'X-WP-Nonce': themeData.nonce
                }}
            )
            .then((res) => {
                console.log('Hurra');
                console.log(res);
            })
            .catch((err) => console.error(err))
    }

}

export default MyNotes;