<script>
    import { currentUser, allUserBugs } from '../../store';
    import bugSVG from '../icons/bug_icon.svg';
    import HomeBug from './HomeBug.svelte';

    const getAllBugs = async() => {
        if($allUserBugs.length > 0) return $allUserBugs;
        let bugs = [];
        for(const project of $currentUser.projects) {
            try {
                const res = await fetch(`/api/projects/bugs?id=${project.idProject}`);
                if(!res.ok) throw new Error(res);
                const json = await res.json();
                bugs = [...bugs, ...json];
            } catch(e) {
                throw new Error(e);
            }
        }
        bugs.sort((a, b) => (a.submittedAt < b.submittedAt) ? 1 : -1);
        allUserBugs.set(bugs);
        return bugs.slice(0, 5);
    }
</script>

<div class="user-bug-info-container user-item-info-container">
    <div class="info-label-container">
        <div class="info-img-wrapper"><img src={bugSVG} alt="bug icon"></div>
        <span>Latest Bugs</span>
    </div>
    <div class="info-container">
        <ol class="info-list">
            {#await getAllBugs()}
            Loading...
            {:then list} 
                {#if list.length > 0}
                    {#each list as bug}
                        <HomeBug bug={bug} />
                    {/each}
                {:else}
                <h6>You have no bugs</h6>
                {/if} 
            {/await}   
        </ol>
    </div>
</div>

<style>
    .user-item-info-container {
        width: var(--clamped-width);
        height: fit-content;
    }

    .info-label-container {
        width: 100%;
        height: 5rem;
        padding: 0.5rem;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 1rem;
    }

    .info-img-wrapper {
        height: 75%;
        width: fit-content;
        background-color: var(--primary-blue);
        padding: 0.4rem;
        border-radius: 25%;
        display: grid;
        place-items: center;
    }

    .info-img-wrapper > img {
        height: 100%;
    }

    .info-label-container > span{
        height: 4rem;
        font-size: 1.25rem;
        line-height: 4rem;
    }

    .info-container {
        min-height: 2rem;
        height: fit-content;
        border-radius: 10px;
        box-shadow: 2px 2px 7px 3px #777;
    }

    .info-list {
        list-style: decimal;
        list-style-position: inside;
        width: 100%;
        margin: 0;
        padding: 0.5rem;
    }
</style>