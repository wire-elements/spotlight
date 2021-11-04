import Fuse from 'fuse.js'

window.LivewireUISpotlight = (config) => {
    return {
        inputPlaceholder: config.placeholder,
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
            this.commandSearch = new Fuse(this.commands, {threshold: 0.3, keys: ['name', 'description', 'synonyms']});
            this.dependencySearch = new Fuse([], {threshold: 0.3, keys: ['name', 'description', 'synonyms']});

            this.$watch('dependencyQueryResults', value => { this.dependencySearch.setCollection(value) });

            this.$watch('input', value => {
                if (value.length === 0) {
                    this.selected = 0;
                }
                if(this.selectedCommand !== null && this.currentDependency !== null && this.currentDependency.type === 'search'){
                    this.$wire.searchDependency(this.selectedCommand.id, this.currentDependency.id, value, this.resolvedDependencies);
                }
            });

            this.$watch('isOpen', value => {
                if (value === false) {
                    setTimeout(() => {
                        this.input = '';
                        this.inputPlaceholder = config.placeholder;
                        this.searchEngine = 'commands';
                        this.resolvedDependencies = {};
                        this.selectedCommand = null;
                        this.currentDependency = null;
                        this.selectedCommand = null;
                        this.requiredDependencies = [];
                    }, 300);
                }
            });
        },

        isOpen: false,
        toggleOpen() {
            if (this.isOpen) {
                this.isOpen = false;
                return;
            }
            this.input = ''
            this.isOpen = true
            setTimeout(() => {
                this.$refs.input.focus()
            }, 100)
        },
        input: '',
        filteredItems() {
            if (this.searchEngine === 'commands') {
                return this.commandSearch.search(this.input).map((item, i) => [item, i])
            }

            if (this.searchEngine === 'search') {
                return this.dependencySearch.search(this.input).map((item, i) => [item, i])
            }

            return [];
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
                let dependencyValue;

                if(this.currentDependency.type === 'search') {
                    dependencyValue = id ? id : this.filteredItems()[this.selected][0].item.id;
                }  else {
                    dependencyValue = this.input;
                }

                this.resolvedDependencies[this.currentDependency.id] = dependencyValue;
            }

            if (this.requiredDependencies.length > 0) {
                this.input = '';
                this.currentDependency = this.requiredDependencies.pop();
                this.inputPlaceholder = this.currentDependency.placeholder;
                this.searchEngine = (this.currentDependency.type === 'search') ? 'search' : false;
            } else {
                this.isOpen = false;
                this.$wire.execute(this.selectedCommand.id, this.resolvedDependencies);
            }
        },
        selected: 0,
    };
};
