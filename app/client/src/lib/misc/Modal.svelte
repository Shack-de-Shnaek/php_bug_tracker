<script>
    import { fade } from 'svelte/transition';
    import { modalControl } from '../../store';
    import errorSVG from '../icons/error_icon.svg';
    import successSVG from '../icons/success_icon.svg';
    
    let type;
    let content;
    let visible;

    modalControl.subscribe(value => {
        if(value !== undefined) {
            type = value.type;
            content = value.content;
            visible = value.visible; 
        }
    });
</script>

{#if visible === true}
<div class="modal-container" transition:fade>
    <div class="modal" on:load={e => e.target.focus}>
        <div class="modal-icon-wrapper {type}-modal">
            {#if type == 'success'}
            <img src={successSVG} alt="modal icon">
            {:else if type == 'error'}
            <img src={errorSVG} alt="modal icon">
            {/if}
        </div>
        <div class="modal-content">{content}</div>
        <button class="hide-button" on:click={modalControl.hide}>Dismiss</button>
    </div>
</div>
{/if}

<style>
    .modal-container {
        /* z-index: -100; */
        position: absolute;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        padding: 0.5rem;
    }

    .modal {
        z-index: 500 !important;
        width: clamp(250px, 25vw, 400px);
        height: fit-content;
        margin: 0 auto;
        padding: 0.25rem;
        border-radius: 10px;
        border: 2px solid var(--primary-blue);
        background-color: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        overflow: hidden;
    }

    .modal-icon-wrapper {
        z-index: inherit;
        min-width: 50px;
        width: 15%;
    }

    .modal-icon-wrapper > img {
        z-index: inherit;
        width: 100%;
        height: auto;
        aspect-ratio: 1 / 1;
    }

    .modal-content {
        z-index: inherit;
        width: 100%;
        padding: 5px;
        font-size: 1rem;
    }

    .hide-button {
        z-index: inherit;
        width: fit-content;
        height: fit-content;
        padding: 0.5rem;
        background-color: var(--primary-blue);
    }
</style>