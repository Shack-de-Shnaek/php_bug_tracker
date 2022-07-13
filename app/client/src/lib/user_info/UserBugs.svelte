<script>
    import { viewedUser } from '../../store';
    import BugListItem from './BugListItem.svelte';
    import capitalizeFirst from '../../services/capitalizeFirst';

    
    export let type = 'bug';
    export let icon;

    let lists;

    viewedUser.subscribe(value => {
        lists = value.bugs;
    });
    
    let category = 'submitted';
</script>

<div class="user-{type}-info-container user-item-info-container">
    <div class="info-label-container">
        <div class="info-img-wrapper"><img src={icon} alt="{type} icon"></div>
        <span>{capitalizeFirst(type)}s</span>
    </div>
    <div class="info-container">
        <div class="selector-wrapper">
            <div class="category" on:click={() => category = 'submitted'} class:selected={category === 'submitted'}>submitted</div>
            <div class="category" on:click={() => category = 'fixed'} class:selected={category === 'fixed'}>fixed</div>
        </div>
        <ol class="info-list">
            {#if lists}
                {#if category === 'submitted'}
                        {#if lists[category].length > 0}
                            {#each lists[category] as bug}
                            <BugListItem data={bug} type={type} />
                            {/each}
                        {:else}
                        <h6>User has no {category} {type}s</h6>
                        {/if}
                {:else}
                    {#if lists[category].length > 0}
                        {#each lists[category] as bug}
                        <BugListItem data={bug} type={type} />
                        {/each}
                    {:else}
                    <h6>User has no {category} {type}s</h6>
                    {/if}
                {/if}
            {/if}
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

    .selector-wrapper {
        width: 100%;
        height: fit-content;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        border-bottom: 1px solid var(--primary-dark);
        overflow: hidden;
    }

    .category {
        width: 50%;
        padding: 10px;
        background-color: inherit;
        font-size: 1.2rem;
        text-align: center;
        user-select: none;
        cursor: pointer;
        transition: 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .category:hover {
        background-color: var(--secondary-yellow);
    }
    
    .category.selected {
        background-color: var(--primary-blue);
    }

    .info-list {
        list-style: decimal;
        list-style-position: inside;
        width: 100%;
        margin: 0;
        padding: 0.5rem;
    }
</style>