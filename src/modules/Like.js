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
            if (response.data != "Only logged in users can create a like.") {
                box.setAttribute("data-exists", "yes")
                var likeCount = parseInt(box.querySelector(".like-count").innerHTML, 10)
                likeCount++
                box.querySelector(".like-count").innerHTML = likeCount
                box.setAttribute("data-like", response.data)
            }

        } catch(err) {
            console.log(err);
        }
    }

    deleteLike = async (box) => {
        try {
            const response = await axios({
                url: `${themeData.root_url}/wp-json/university/managelike`,
                method: 'delete',
                data: { "like": box.getAttribute("data-like") },
            })
            box.setAttribute("data-exists", "no")
            var likeCount = parseInt(box.querySelector(".like-count").innerHTML, 10)
            likeCount--
            box.querySelector(".like-count").innerHTML = likeCount
            box.setAttribute("data-like", "")
            console.log(response.data)
        } catch(err) {
            console.log(err);
        }
    }
}

export default Like;