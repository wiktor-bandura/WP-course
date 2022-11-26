import axios from "axios";

class Like {
    constructor() {
        if(document.querySelector('.like-box')) {
            axios.defaults.headers.common["X-WP-Nonce"] = themeData.nonce;
            this.likeBox = document.querySelector('.like-box');
            this.events();
        }
    }

    events() {
        this.likeBox.addEventListener('click', this.clickDispatcher)
    }

    clickDispatcher = (e) => {
        const currentLikeBox = e.target.closest('.like-box');

        if(this.likeBox.getAttribute('data-exists') === 'yes') {
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }
    }

    createLike = async (box) => {
        try {
            const response = await axios.post(`${themeData.root_url}/wp-json/university/managelike`, {
                professorId: box.getAttribute('data-professor'),
            });
            box.setAttribute('data-exist', 'yes');
            let likeCount = parseInt(box.querySelector('.like-count').innerHTML);
            likeCount++;
            console.log(likeCount);
            console.log(response.data);
            box.querySelector('.like-count').innerHTML = likeCount;

        } catch(err) {
            console.log(err);
        }
    }

    deleteLike = async (box) => {
        try {
            const response = await axios.delete(`${themeData.root_url}/wp-json/university/managelike`, {
                'like': box.getAttribute('data-like'),
            });
            console.log(response.data);

        } catch(err) {
            console.log(err);
        }
    }
}

export default Like;