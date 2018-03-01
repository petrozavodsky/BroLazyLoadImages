if (window.addEventListener && document.getElementsByClassName) {
    window.addEventListener('load', function () {

        var pItem = document.querySelectorAll('[data-lazy-src]');
        var pCount, timer;

        window.addEventListener('scroll', scroller, false);
        window.addEventListener('resize', scroller, false);

        if (MutationObserver) {
            var observer = new MutationObserver(function () {
                if (pItem.length !== pCount) {
                    inView();
                }
            });
            observer.observe(document.body, {subtree: true, childList: true, attributes: true, characterData: true});
        }

        inView();


        function scroller() {

            timer = timer || setTimeout(function () {
                timer = null;
                inView();
            }, 200);

        }

        function inView() {

            if (pItem.length) {

                var wT = window.pageYOffset, wB = wT + window.innerHeight, cRect, pT, pB;
                var p = 0;

                while (p < pItem.length) {

                    cRect = pItem[p].getBoundingClientRect();
                    pT = wT + cRect.top;
                    pB = pT + cRect.height;

                    if (wT < pB && wB > pT) {
                        loadFullImage(pItem[p], p);
                    }
                    p++;
                }
                pCount = pItem.length;
            }

        }

        function loadFullImage(item, index) {

            function payload(item) {
                var src = item.getAttribute('data-lazy-src');
                var srcset = false;
                var sizes = false;


                if (item.hasAttribute('data-lazy-src')) {
                    srcset = item.getAttribute('data-lazy-srcset');
                    sizes = item.getAttribute('sizes');

                    var img = new Image();

                    img.src = src;
                    if (srcset) {
                        img.srcset = srcset;
                    }
                    if (sizes) {
                        img.sizes = sizes;
                    }
                    if (item.getAttribute('alt')) {
                        img.alt = item.getAttribute('alt');
                    }
                    img.height = item.height;
                    img.width = item.width;
                    img.className = item.classList.value + ' animated progressive';

                    if (img.complete) {
                        addImg(item);
                    } else {
                        img.onload = addImg(item);
                    }

                    function addImg(item) {

                        if (item) {

                            item.style.visibility = 'hidden';

                            item.removeAttribute('data-lazy-src');

                            item.parentNode.appendChild(img).addEventListener('animationend', function (e) {
                                e.target.classList.remove('animated');
                            });

                        }
                    }

                }
            }

            if (window.requestAnimationFrame) {

                requestAnimationFrame(function () {
                    payload(item);
                });

            } else {
                payload(item);
            }

        }

    }, false);
}