export default class Paginator {
    constructor(data) {
        this.current_page = data.current_page;
        this.data = data.data;
        this.first_page_url = data.first_page_url;
        this.from = data.from;
        this.last_page = data.last_page;
        this.last_page_url = data.last_page_url;
        this.next_page_url = data.next_page_url;
        this.path = data.path;
        this.per_page = data.per_page;
        this.prev_page_url = data.prev_page_url;
        this.to = data.to;
        this.total = data.total;
        this.filters = {};
    }

    refreshData(data) {
        this.current_page = data.current_page;
        this.data = data.data;
        this.first_page_url = data.first_page_url;
        this.from = data.from;
        this.last_page = data.last_page;
        this.last_page_url = data.last_page_url;
        this.next_page_url = data.next_page_url;
        this.path = data.path;
        this.per_page = data.per_page;
        this.prev_page_url = data.prev_page_url;
        this.to = data.to;
        this.total = data.total;
    }

    addFilter(key, value) {
        this.filters[key] = value;
    }

    removeFilter(key) {
        if (this.filters.hasOwnProperty(key)) {
            delete this.filters[key];
        }
    }

    removeAllFilters() {
        this.filters = null;
    }

    getData() {
        return this.data;
    }

    getLastPageNumber() {
        return this.last_page;
    }

    getCurrentPageNumber() {
        return this.current_page;
    }

    parseFilters() {
        let filters = '';

        for (let property in this.filters) {
            if (this.filters.hasOwnProperty(property)) {
                filters += '&' + property + '=' + this.filters[property];
            }
        }

        return filters;
    }

    loadPage(pageNumber) {
        let filters = this.parseFilters();

        return new Promise((resolve, reject) => {
            axios.get(this.path + '?page=' + pageNumber + filters)
                .then(response => {
                    this.refreshData(response.data);
                    resolve('Success');
                })
                .catch(error => {
                    reject('Could not load last page');
                })
        });
    }

    loadNextPage() {
        if (this.current_page < this.last_page) {
            this.loadPage(parseInt(this.current_page) + 1)
                .then(response => {
                    return true;
                })
                .catch(error => {
                    return false;
                });
        }
    }

    loadLastPage() {
        if (this.current_page < this.last_page) {
            this.loadPage(parseInt(this.last_page))
                .then(response => {
                    return true;
                })
                .catch(error => {
                    return false;
                });
        }
    }

    loadFirstPage() {
        if (1 < this.current_page) {
            this.loadPage(1)
                .then(response => {
                    return true;
                })
                .catch(error => {
                    return false;
                });
        }
    }

    loadPreviousPage() {
        if (1 < this.current_page) {
            this.loadPage(parseInt(this.current_page) - 1)
                .then(response => {
                    return true;
                })
                .catch(error => {
                    return false;
                });
        }
    }
}