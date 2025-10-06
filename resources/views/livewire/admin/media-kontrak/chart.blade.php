<div>
    <div class="row">
        <div class="col-12">
            <div class="title-header option-title">
                <h5>
                    Riwayat Kontrak Media
                </h5>
                <div class="">
                    <a href="{{ route('a.media-kontrak.detail', $pers->unique_id) }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Detail
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card o-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        {{ $pers->nama_perusahaan }}
                    </h5>
                </div>
                <h5 class="mb-0">
                    {{ $pers->nama_media }}
                </h5>

                <div class="card-body mt-4">
                    <div
                        style="min-width: 100%; height: 420px; overflow-x: auto; overflow-y:hidden; position: relative;">
                        <livewire:livewire-column-chart key="{{ $chartRiwayat->reactiveKey() }}" style="width:100%"
                            :column-chart-model="$chartRiwayat" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Format rupiah function
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        // Override ApexCharts default options before charts are rendered
        if (typeof window.ApexCharts !== 'undefined') {
            // Set global defaults for all charts
            ApexCharts.setGlobalOptions = ApexCharts.setGlobalOptions || function() {};
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Function to format chart labels
            function formatChartLabels() {
                setTimeout(function() {
                    // Format Y-axis labels
                    // const yAxisLabels = document.querySelectorAll('.apexcharts-yaxis-label');
                    // yAxisLabels.forEach(function(label) {
                    //     const originalText = label.textContent;
                    //     const value = parseFloat(originalText.replace(/[^\d.-]/g, ''));
                    //     if (!isNaN(value) && value >= 0 && !originalText.includes('Rp')) {
                    //         label.textContent = formatRupiah(value);
                    //     }
                    // });

                    // Format data labels on bars
                    const dataLabels = document.querySelectorAll('.apexcharts-datalabel');
                    dataLabels.forEach(function(label) {
                        const originalText = label.textContent;
                        const value = parseFloat(originalText.replace(/[^\d.-]/g, ''));
                        if (!isNaN(value) && value >= 0 && !originalText.includes('Rp')) {
                            label.textContent = formatRupiah(value);
                        }
                    });
                }, 300);
            }

            // Wait for Livewire to finish rendering
            if (typeof Livewire !== 'undefined') {
                Livewire.hook('element.updated', (el, component) => {
                    formatChartLabels();
                });
            }

            // Initial formatting
            formatChartLabels();

            // Observer for dynamic content changes
            const chartContainer = document.querySelector('[wire\\:id]');
            if (chartContainer) {
                const observer = new MutationObserver(function(mutations) {
                    let shouldUpdate = false;
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                            shouldUpdate = true;
                        }
                    });
                    if (shouldUpdate) {
                        formatChartLabels();
                    }
                });

                observer.observe(chartContainer, {
                    childList: true,
                    subtree: true
                });
            }

            // Handle tooltip formatting on hover
            document.addEventListener('mouseover', function(e) {
                if (e.target.closest('.apexcharts-tooltip')) {
                    setTimeout(() => {
                        const tooltipValues = document.querySelectorAll('.apexcharts-tooltip-text-y-value');
                        tooltipValues.forEach(function(tooltip) {
                            const originalText = tooltip.textContent;
                            const value = parseFloat(originalText.replace(/[^\d.-]/g, ''));
                            if (!isNaN(value) && value >= 0 && !originalText.includes('Rp')) {
                                tooltip.textContent = formatRupiah(value);
                            }
                        });
                    }, 10);
                }
            });

            // Additional formatting after window load
            window.addEventListener('load', function() {
                setTimeout(formatChartLabels, 1000);
                setTimeout(formatChartLabels, 2000);
            });
        });
    </script>
    @endpush
</div>
