var app = new Vue({
	el: '#app',
	data: {
		login: '',
		pass: '',
		post: false,
		invalidLogin: false,
		commentUser: null,
		invalidPass: false,
		invalidSum: false,
		userNotFound: false,
		posts: [],
			addSum: 1,
		amount: null,
		likes: null,
		postLikes: 0,
		commentLikes: 0,
		commentId: null,
		balance: null,
		commentText: '',
		packs: [
			{
				id: 1,
				price: 5
			},
			{
				id: 2,
				price: 20
			},
			{
				id: 3,
				price: 50
			},
		],
	},
	computed: {
		test: function () {
			return [];
		}
	},
	created(){
		var self = this
		axios
			.get('/main_page/get_all_posts')
			.then(function (response) {
				self.posts = response.data.posts;
			})
	},
	methods: {
		logout: function () {
			console.log ('logout');
		},
		logIn: function () {
			var self= this;
			if(self.login === ''){
				self.invalidLogin = true
			}
			else if(self.pass === ''){
				self.invalidLogin = false
				self.invalidPass = true
			}
			else{
				self.invalidLogin = false
				self.invalidPass = false
				axios.post('/main_page/login', {
					login: self.login,
					password: self.pass
				})
					.then(function (response) {
						if (response.data.status === 'success') {
							setTimeout(function () {
								$('#loginModal').modal('hide');
								setTimeout(function () {
									location.reload()
								}, 500);
							}, 300);
						} else {
							self.userNotFound = true
						}
					})
			}
		},
		fillIn: function () {
			let self = this;
			if(self.addSum === 0){
				self.invalidSum = true
			} else{
				self.invalidSum = false
				axios.post('/main_page/add_money', {
					sum: self.addSum,
				})
					.then(function (response) {
						console.log(response)
						// setTimeout(function () {
						// 	$('#addModal').modal('hide');
						// 	setTimeout(function () {
						// 		location.reload()
						// 	}, 500);
						// }, 300);
					})
			}
		},
		openPost: function (id) {
			let self = this;
			axios
				.get('/main_page/get_post/' + id)
				.then(function (response) {
					self.post = response.data.post;
					if(self.post){
						setTimeout(function () {
							$('#postModal').modal('show');
						}, 500);
					}
				})
		},
		addLike: function (id) {
			let self = this;
			axios
				.post('/main_page/like', {
					id: id
				})
				.then(function (response) {
					if (response.data.status === 'success') {
						self.likes = response.data.likes;
						self.postLikes = response.data.post.likes;
					}
				})

		},
		addCommentLike: function (id) {
			let self = this;
			axios
				.post('/main_page/comment_like', {
					id: id
				})
				.then(function (response) {
					if (response.data.status === 'success') {
						self.post = response.data.post,
						self.likes = response.data.likes
					}
				})

		},
		buyPack: function (id) {
			let self= this;
			axios.post('/main_page/buy_boosterpack', {
				id: id,
			})
				.then(function (response) {
					if(response.data.status === 'success'){
						self.amount = response.data.amount
						self.likes = response.data.likes
						self.balance = response.data.balance

						setTimeout(function () {
							$('#amountModal').modal('show');
						}, 500);
					}
				})
		},
		createComment: function (id, commentText, parentId = 0) {
			let self= this;
			console.log(parentId)
			axios.post('/main_page/comment', {
				id: id,
				commentText: commentText,
				parentId: parentId,
			})
				.then(function (response) {
					if (commentText !== '') {
						setTimeout(function () {
							console.log(response)
							self.post = response.data.post
							self.commentText = ''
						}, 250);
					}
				})
		}
	}
});

