<script>
    import { viewedUser } from '../../store';
    import capitalizeFirst from '../../services/capitalizeFirst';
    import InfoListItem from './InfoListItem.svelte';

    export let type = '';
    export let icon;
    
    let list;

    viewedUser.subscribe(value => {
        list = value[`${type}s`];
    });
</script>

<div class="user-{type}-info-container user-item-info-container">
    <div class="info-label-container">
        <div class="info-img-wrapper"><img src={icon} alt="{type} icon"></div>
        <span>{capitalizeFirst(type)}s</span>
    </div>
    <div class="info-container">
        <ol class="info-list">
            {#if list}
                {#if list.length > 0}
                    {#each list as item, i}
                    <InfoListItem data={item} type={type} />
                    {/each}
                {:else}
                <h6>User has no {type}s</h6>
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
        aspect-ratio: 1 / 1;
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