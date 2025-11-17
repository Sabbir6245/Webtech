
let articleComments = {
    article1: [],
    article2: [],
    article3: []
};


document.querySelectorAll(".stars").forEach(starBox => {
   
    for (let i = 1; i <= 5; i++) {
        let star = document.createElement("span");
        star.textContent = "☆";
        star.dataset.value = i;
        starBox.appendChild(star);
    }


    starBox.addEventListener("click", event => {
        if (event.target.tagName !== "SPAN") return;

        let selectedValue = Number(event.target.dataset.value);
        starBox.dataset.selected = selectedValue;

        
        [...starBox.children].forEach(s => {
            s.textContent = Number(s.dataset.value) <= selectedValue ? "★" : "☆";
        });
    });
});


document.querySelectorAll(".comment-wrapper").forEach(wrapper => {
    
    const form = wrapper.querySelector(".comment-form");
    const errorBox = wrapper.querySelector(".error-message");
    const commentList = wrapper.querySelector(".comment-list");
    const totalCommentsEl = wrapper.querySelector(".total-comments");
    const avgRatingEl = wrapper.querySelector(".average-rating");

    const articleID = wrapper.dataset.article;

    
    form.addEventListener("submit", event => {
        event.preventDefault();
        errorBox.textContent = ""; 

       
        let name = form.querySelector(".name-input").value.trim();
        let email = form.querySelector(".email-input").value.trim();
        let comment = form.querySelector(".comment-input").value.trim();
        let ratingBox = form.querySelector(".stars");
        let rating = ratingBox.dataset.selected ? Number(ratingBox.dataset.selected) : null;


        if (name.length < 2 || name.length > 50) {
            return showError("Name should be between 2 and 50 characters");
        }

        if (email && !email.includes("@")) {
            return showError("Please enter a valid email address");
        }

        if (comment.length < 1 || comment.length > 500) {
            return showError("Comment should be between 10 and 500 characters");
        }


       
        articleComments[articleID].push({ name, email, comment, rating });


        form.reset();
        resetStars(ratingBox);


       
        updateUI(articleComments[articleID]);
    });

    
    
    function showError(message) {
        errorBox.textContent = message;
    }

    
    function resetStars(starBox) {
        starBox.dataset.selected = "";
        starBox.querySelectorAll("span").forEach(s => (s.textContent = "☆"));
    }

    
    function updateUI(comments) {
        commentList.innerHTML = "";

        
        comments.forEach(c => {
            let div = document.createElement("div");
            div.className = "comment-item";
            div.innerHTML = `
                <strong>${c.name}</strong> ${c.rating ? ` - ${c.rating} ★` : ""}
                <p>${c.comment}</p>
            `;
            commentList.appendChild(div);
        });

        
        totalCommentsEl.textContent = `${comments.length} Comments`;

       
        let ratings = comments.filter(c => c.rating).map(c => c.rating);
        let avg = ratings.length
            ? (ratings.reduce((a, b) => a + b) / ratings.length).toFixed(1)
            : 0;

        avgRatingEl.textContent = `Average Rating: ${avg} ★`;
    }
});
