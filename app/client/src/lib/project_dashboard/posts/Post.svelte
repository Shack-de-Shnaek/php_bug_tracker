<script>
    import { currentUser, viewedProject, viewedUser, userIsProjectLeader, modalControl } from '../../../store';
    import capitalizeFirst from '../../../services/capitalizeFirst';
    
    export let post;

    const getAuthor = async() => {
        if(post.authorId == $currentUser.idUser) return $currentUser;
        if(post.authorId == $viewedUser.idUser) return $viewedUser;
        try {
            const res = await fetch(`/api/posts/author?id=${post.idPost}`);
            const json = await res.json();
            return json[0];
        } catch(e) {
            console.log(e);
            return 'Error';
        }
    }
    
    const handleDeletePost = async(postId) => {
        try {
            const data = `${encodeURIComponent('id')}=${encodeURIComponent(postId)}`;
            const res = await fetch(`/api/posts`, {
                method: 'DELETE',
                body: data
            });
            if(res.ok) {
                modalControl.show('success', 'Post deleted');
                viewedProject.update(project => {
                    project.posts = project.posts.filter(projectPost => projectPost.idPost != post.idPost);
                    return project;
                });
            } else throw new Error;
            return res;
        } catch(e) {
            modalControl.show('error', 'Could not delete the post');
            console.log(e);
        }
    }
</script>

<div class="post-container general-container">
    <div class="post-header">
        <div class="post-title">{post.title}</div>
        {#await getAuthor()}
        Loading...
        {:then author} 
        <a class="post-author" href={`/users/${author.idUser}`}>{capitalizeFirst(author.name)} {capitalizeFirst(author.surname)}</a>
        {/await}
    </div>
    <hr>
    <div class="post-content">
        {post.contents}
    </div>
    <hr>
    <div class="post-date">
        <span>{post.createdAt}</span>
        {#if ($userIsProjectLeader == true) || ($currentUser.idUser == post.authorId)}
        <button on:click={() => handleDeletePost(post.idPost)} class="delete-button">Delete</button>
        {/if}
    </div>
</div>

<style>
    .post-header {
        width: 100%;
        margin: 0.5rem 0;
        padding: 0 0.25rem;
    }

    .post-title {
        font-size: 1.5rem;
        font-weight: 500;
    }

    .post-author {
        display: block;
    }   

    .post-content {
        width: 100%;
        margin-bottom: 1rem;
        padding: 0.5rem 0.25rem;
    }

    .post-date {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .delete-button {
        background-color: var(--secondary-red);
    }
</style>