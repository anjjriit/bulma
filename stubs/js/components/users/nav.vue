<template>
	<div class="nav-right is-flex" @mouseleave="visible = false">
		<a class="nav-item" @mouseover="visible = true">
			<span class="icon">
				<i class="fa fa-user"></i>
			</span>
			{{ user.name }}
		</a>
		<div class="box" v-show="visible">
			<ul class="menu-list">
				<li><a href="/logout">Logout</a></li>
			</ul>
		</div>
	</div>
</template>

<style scoped>
	.box {
		right: 0;
		position: absolute;
		top: 100%;
		z-index: 1000;
		padding: 1.5rem 3rem 1.5rem;
	}

	.menu-list a:hover {
		background-color: transparent;
	}
</style>

<script>
    export default {
		/*
		 * The component's data.
		 */
        data() {
            return {
                visible: false,
                user: []
            };
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {

            /**
             * Prepare the component (Vue 2.x).
             */
            prepareComponent() {
                this.getUser();
            },

            /**
             * Get the current auth user
             */
            getUser() {
                axios.get('/api/user')
                    .then(response => {
                        this.user = response.data;
                    });
            }
        }
    }
</script>