const CACHE_NAME = "wasiqhari-v1";
const urlsToCache = [
    "/",
    "/dashboard",
    "/css/styles.css",
    "/offline.html" // Opcional: crea un archivo simple por si no hay red
];

// Instalación
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(urlsToCache);
        })
    );
});

// Activación y limpieza de caché vieja
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Interceptar peticiones (Estrategia: Network First, luego Cache)
self.addEventListener("fetch", (event) => {
    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Si la red responde, guardamos una copia fresca en caché
                if (!response || response.status !== 200 || response.type !== "basic") {
                    return response;
                }
                const responseToCache = response.clone();
                caches.open(CACHE_NAME).then((cache) => {
                    cache.put(event.request, responseToCache);
                });
                return response;
            })
            .catch(() => {
                // Si la red falla, intentamos servir desde la caché
                return caches.match(event.request);
            })
    );
});