<script>
    import { currentUser, allUserPosts } from "../store";
    import { onMount } from "svelte";
    import MainPagePost from "../lib/posts_info/MainPagePost.svelte";

    const getAllPosts = async() => {
        if($allUserPosts.length > 0) return $allUserPosts;
        let posts = [];
        for(const project of $currentUser.projects) {
            try {
                const res = await fetch(`/api/projects/posts?id=${project.idProject}`);
                if(!res.ok) throw new Error(res);
                const json = await res.json();
                posts = [...posts, ...json];
            } catch(e) {
                throw new Error(e);
            }
        }
        posts.sort((a, b) => (a.createdAt < b.createdAt) ? 1 : -1);
        allUserPosts.set(posts);
        return posts;
    }

    let allPosts = [];
    let shownPosts = [];
    let postSelectorValue = 'all';

    allUserPosts.subscribe(value => {
        allPosts = value;
    });

    $: {
        shownPosts = (postSelectorValue === 'all') ? allPosts : allPosts.filter(post => post.projectId === postSelectorValue);
    }

    onMount(() => getAllPosts());
</script>

<svelte:head>
    <title>Posts | Bug Tracker</title>
</svelte:head>
<main>
    <div class="post-page-header">
        <span class="title">Posts</span>
        <select class="post-selector" bind:value={postSelectorValue} on:input={(e) => e.target.blur()}>
            <option value="all">All posts</option>
            {#each $currentUser.projects as project}
            <option value={project.idProject}>{project.name}</option>
            {/each}
        </select>
    </div>
    <div class="post-list">
        {#if shownPosts.length > 0}
            {#each shownPosts as post}
            <MainPagePost post={post} project={$currentUser.projects.filter(project => project.idProject === post.projectId)[0]} />
            {/each}
        {:else}
        <h2 style="margin: 2rem auto;">There are no posts in this category</h2>
        {/if}
    </div>
</main>

<style>
    .post-page-header {
        min-width: 300px;
        width: 100%;
        height: fit-content;
        max-height: 5rem;
        padding: 0.5rem 0.75rem;
        font-size: clamp(1.25rem, 3vw, 2rem);
        border-bottom: 2px solid var(--primary-blue);
        display: flex;
        align-items: flex-end;
        gap: 2rem;
    }
    
    .post-list {
        width: 100%;
        margin-top: 3rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2rem;
    }

    .post-selector {
        width: fit-content;
        margin: 0;
        padding: clamp(1px, 0.3%, 4px);
        border-radius: 5px;
        border: 2px solid #ccc;
        font-size: 1rem;
        outline: none;   
        user-select: none;
        transition: border-color 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .post-selector:hover {
        border-color: var(--secondary-orange);
    }

    .post-selector:focus {
        border-color: var(--secondary-yellow);
    }

</style>