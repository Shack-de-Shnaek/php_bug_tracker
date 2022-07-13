<script>
    import { router } from 'tinro';
    import { currentUser, viewedUser, viewedProject, modalControl } from '../../store';
    import capitalizeFirst from '../../services/capitalizeFirst';
    import parseDate from '../../services/parseDate';

    export let data = {};
    export let type = '';

    let user;

    viewedUser.subscribe(value => {
        user = value;
    });

    let idName = 'id'+capitalizeFirst(type);
    let open = false;

    const handleDeletePost = async(postId) => {
        try {
            const data = `${encodeURIComponent('id')}=${encodeURIComponent(postId)}`;
            const res = await fetch(`/api/posts`, {
                method: 'DELETE',
                body: data
            });
            if(res.ok) {
                modalControl.show('success', 'Post deleted');
                currentUser.update(value => {
                    value.posts = value.posts.filter(filterPost => filterPost.idPost !== postId);
                    return value;
                });
                viewedUser.update(value => {
                    value.posts = value.posts.filter(filterPost => filterPost.idPost !== postId);
                    return value;
                });
            } else throw new Error();
            return res;
        } catch(e) {
            modalControl.show('error', 'Could not delete the post');
            console.log(e);
        }
    }
    
    const getProject = async() => {
        if (data.projectId == $viewedProject.idProject) return $viewedProject;
        try {
            const res = await fetch(`/api/projects?id=${data.projectId}`);
            const json = await res.json();
            return json[0];
        } catch(e) {
            throw new Error(e);
        }
    }
</script>

<li class="info-list-item" class:open={open}>
    <div class="label" on:click={() => open = !open}>
        <span class="title"> {(data.name) ? data.name : data.title}</span>
        {#if type === 'post'}
            <span class="post-date">{parseDate(data.createdAt)}</span>
        {/if}
    </div>
    <div class="info">
        <div class="item">
            {#if type === 'post'}
                <div class="button-container">
                {#await getProject()}
                ...
                {:then project} 
                <button class="project-link" on:click={() => router.goto(`/projects/${project.idProject}`)}>From project: {project.name}</button>
                {/await}
                {#if user.idUser == $currentUser.idUser}
                <button on:click={() => handleDeletePost(data.idPost)} class="delete-button">Delete</button>
                {/if}
                </div>
            {/if}
            {#if type === 'project'}
            <button on:click={() => router.goto(`/${type}s/${data[idName]}`)} class="info-button">More Details</button>
            {/if}
            <div>
                {(data.description) ? data.description : data.contents}
            </div>
        </div>
    </div>
</li>

<style>
    .info-list-item {
        max-width: 100%;
        height: fit-content;
        font-size: 1.05rem;
        margin: 0.25rem 0;
        padding: 5px;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0;
        overflow: hidden;
        transition: 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .info-list-item:nth-child(even) {
        background-color: #eee;
    }

    .info-list-item::marker {
        display: inline;
    }

    .info-list-item .label {
        height: 100%;
        width: 100%;
        margin: 0;
        font-size: 1.25rem;
        user-select: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .info-list-item .label .title {
        max-width: 80%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .info-list-item .info {
        max-height: 0;
        width: 100%;
        margin: 0;
        padding: 0 5px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        background-color: var(--primary-white);
        font-size: 1rem;
        overflow: hidden;
        transition: max-height 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .item {
        width: 100%;
    }

    .info-list-item.open {
        background-color: var(--secondary-yellow);
        gap: 0.5rem;
    }

    .info-list-item.open .label {
        display: flex;
        justify-content: space-between;
    }

    .info-list-item.open .label .title {
        max-width: 80%;
        white-space: unset;
        overflow: unset;
        text-overflow: unset;
    }

    .info-list-item.open .info {
        max-height: 10rem;
        padding: 5px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }

    .project-link {
        padding: 0.25rem;
    }

    .post-date {
        min-width: fit-content;
        font-size: 0.95rem;
    }

    .info-list-item .info button {
        margin: 0;
        margin-left: 0.5rem;
        border: none;
        padding: 0.25rem;
        border-radius: 10px;
        transition: 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .button-container {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .delete-button {
        background-color: var(--secondary-red);
    }

    .delete-button:hover {
        background-color: var(--secondary-orange);
    }
    
    .delete-button:focus {
        background-color: var(--secondary-orange);
    }
</style>