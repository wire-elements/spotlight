window.LivewireUISpotlight = (config) => {
    return {
        searchPlaceholder: config.placeholder,
        searchEngine: 'commands',
        commands: config.commands,

        commandSearch: null,
        selectedCommand: null,

        dependencySearch: null,
        dependencyQueryResults: window.Livewire.find(config.componentId).entangle('dependencyQueryResults'),

        requiredDependencies: [],
        currentDependency: null,
        resolvedDependencies: {},

        init() {
            this.commandSearch = new Fuse(this.commands, {threshold: 0.3, keys: ['name', 'description']});
            this.dependencySearch = new Fuse([], {threshold: 0.3, keys: ['name', 'description']});

            this.$watch('dependencyQueryResults', value => { this.dependencySearch.setCollection(value) });
            this.$watch('search', value => { if (value.length === 0) selected = 0 });
            this.$watch('search', value => {

                if(this.selectedCommand !== null && this.currentDependency !== null){
                    this.$wire.searchDependency(this.selectedCommand.id, this.currentDependency.id, value, this.resolvedDependencies);
                }
            });
        },

        isOpen: false,
        toggleOpen() {
            if (this.isOpen) {
                this.isOpen = false;
                return;
            }
            this.search = ''
            this.isOpen = true
            setTimeout(() => {
                this.$refs.search.focus()
            }, 100)
        },
        search: '',
        filteredItems() {
            if (this.searchEngine === 'commands') {
                return this.commandSearch.search(this.search).map((item, i) => [item, i])
            }

            return this.dependencySearch.search(this.search).map((item, i) => [item, i])
        },
        selectUp() {
            this.selected = Math.max(0, this.selected - 1)
            this.$nextTick(() => {
                this.$refs.results.children[this.selected + 1].scrollIntoView({
                    block: 'nearest',
                })
            })
        },
        selectDown() {
            this.selected = Math.min(this.filteredItems().length - 1, this.selected + 1)
            this.$nextTick(() => {
                this.$refs.results.children[this.selected + 1].scrollIntoView({
                    block: 'nearest',
                })
            })
        },
        go(id) {
            if (this.selectedCommand === null) {
                this.selectedCommand = this.commands.find((command) => {
                    return command.id === (id ? id : this.filteredItems()[this.selected][0].item.id);
                });
                this.requiredDependencies = JSON.parse(JSON.stringify(this.selectedCommand.dependencies));
            }

            if (this.currentDependency !== null) {
                this.resolvedDependencies[this.currentDependency.id] = id ? id : this.filteredItems()[this.selected][0].item.id;
            }

            if (this.requiredDependencies.length > 0) {
                this.search = '';
                this.searchEngine = 'dependencies';
                this.currentDependency = this.requiredDependencies.pop();
                this.searchPlaceholder = this.currentDependency.placeholder;
            } else {
                this.isOpen = false;
                this.$wire.execute(this.selectedCommand.id, this.resolvedDependencies);

                setTimeout(() => {
                    this.search = '';
                    this.searchPlaceholder = config.placeholder;
                    this.searchEngine = 'commands';
                    this.resolvedDependencies = {};
                    this.selectedCommand = null;
                    this.currentDependency = null;
                    this.selectedCommand = null;
                    this.requiredDependencies = [];
                }, 300);
            }
        },
        selected: 0,
    };
};
