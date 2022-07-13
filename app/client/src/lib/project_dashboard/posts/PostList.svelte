<script>
    import { viewedProject } from '../../../store';
    import Post from "./Post.svelte";
</script>

<div class="post-list">
    {#each $viewedProject.posts as post}
    <Post post={post} />
    {/each}
</div>

<style>
    .post-list {
        width: 100%;
        margin-top: 3rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2rem;
    }
</style>