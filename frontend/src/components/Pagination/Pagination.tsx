type PaginationProps = {
    page: number;
    totalPages: number;
    onPageChange: (page: number) => void;
};

function Pagination({ page, totalPages, onPageChange }: PaginationProps) {
    const goTo = (p: number) => {
        const next = Math.min(totalPages, Math.max(1, p));
        if (next !== page) onPageChange(next);
    };

    return (
        <div className={"pagination"}>
            <button
                className={"pageButton"}
                disabled={page <= 1}
                onClick={() => goTo(page - 1)}
            >
                ←
            </button>

            <span className={"pageInfo"}>
                {page} / {totalPages}
            </span>

            <button
                className={"pageButton"}
                disabled={page >= totalPages}
                onClick={() => goTo(page + 1)}
            >
                →
            </button>
        </div>
    );
}

export default Pagination;
