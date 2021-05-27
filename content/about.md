---
views:
    redovisa:
        region: sidebar-left
        template: anax/v2/image/default
        data:
            src: "image/garden-graphic.jpg&width=500&height=1000&crop-to-fit"
    stats:
        region: after-main
        template: anax/v2/columns/multiple_columns
        data:
            class: info-column
            columns:
                column-1:
                    data:
                        class: col5
                        template: anax/v2/block/default
                        contentRoute: block/info-1
                column-2:
                    data:
                        class: col5
                        template: anax/v2/block/default
                        contentRoute: block/info-2
                column-3:
                    data:
                        class: col5
                        template: anax/v2/block/default
                        contentRoute: block/info-3
                column-4:
                    data:
                        class: col5
                        template: anax/v2/block/default
                        contentRoute: block/info-4
                column-5:
                    data:
                        class: col5
                        template: anax/v2/block/default
                        contentRoute: block/info-5
    dev:
        region: after-main
        template: anax/v2/article/default
        data:
            class: about-dev
            meta:
                type: single
                route: about-dev
---

#### Who we are

Helping gardeners and farmers plant the seeds of the future
=============================================

Our mission is to provide a public platform for anyone with a desire to learn and cultivate the gardens of our planet, from the occasional flower pot on a midtown balcony to the fields of hay.
