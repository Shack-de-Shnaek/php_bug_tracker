<script>
    export let label = 'Submit';
    export let id = '';
</script>

<button type="submit" class="submit-button" id={id}>
    {label}
</button>

<style>
    .submit-button {
        width: fit-content;
        height: fit-content;
        font-size: 1.2rem;
        line-height: calc(1.2rem + 6px);
        color: var(--primary-dark);
        font-weight: 600;
        background-color: var(--primary-blue);
        padding: 3px 1.25rem;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.1s cubic-bezier(0.075, 0.82, 0.165, 1);
    }

    .submit-button:active {
        color: var(--primary-dark);
        background-color: var(--secondary-yellow);
    }
</style>