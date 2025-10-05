<template>
  <div class="min-h-screen bg-gray-50">
    <section class="py-32 px-4">
      <div class="container mx-auto">
        <div class="mb-14 grid gap-5 text-center md:grid-cols-2 md:text-left">
          <h1 class="text-5xl font-semibold">Rólunk</h1>
          <p class="">
            Elkötelezett csapatunk kiváló minőségű autógumikat kínál gyors szállítással,
            versenyképes áron – hogy mindig biztonságban legyél az utakon.
          </p>
        </div>
        <div class="grid gap-7 lg:grid-cols-3">
          <img
            src="/public/replacement-winter-summer-tires.jpg"
            alt="autógumik"
            class="size-full max-h-[620px] rounded-xl object-cover lg:col-span-2"
          />
          <div class="flex flex-col gap-7 md:flex-row lg:flex-col">
            <div
              class="bg-muted flex flex-col justify-between gap-6 rounded-xl p-7 md:w-1/2 lg:w-auto"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="mr-auto h-16 w-16"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9.75 17L6 13.25m0 0L9.75 9.5M6 13.25h12"
                />
              </svg>
              <div>
                <p class="mb-2 text-lg font-semibold">Széles termékválaszték</p>
                <p>
                  Autógumik minden évszakra és járműtípusra – nyári, téli és négyévszakos megoldások
                  raktárról.
                </p>
              </div>
              <router-link
                to="/products"
                class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium transition-all border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 mr-auto"
                >Fedezd fel kínálatunkat</router-link
              >
            </div>
            <img
              src="/public/pile.jpg"
              alt="gumi bolt"
              class="grow basis-0 rounded-xl object-cover md:w-1/2 lg:min-h-0 lg:w-auto"
            />
          </div>
        </div>

        <div class="py-32">
          <p class="text-center font-semibold text-2xl pb-5">Partnereink világszerte</p>
          <div class="mt-8 flex flex-wrap justify-center gap-8">
            <div class="flex items-center gap-3">
              <img
                src="https://logos-world.net/wp-content/uploads/2020/09/Michelin-Logo-700x394.png"
                alt="Michelin Logo"
                class="w-50 h-50 object-contain"
              />
            </div>

            <div class="flex items-center gap-3">
              <img
                src="https://www.billigstautos.com/wp-content/uploads/2013/09/Continental_Logo_gelb_sRGB.png"
                alt="Continental Logo"
                class="w-50 h-50 object-contain"
              />
            </div>
            <div class="flex items-center gap-3">
              <img
                src="https://openbay.s3.amazonaws.com/uploads/production/image_upload/image/3806/original_goodyear_logo_3d.png"
                alt="Goodyear logo"
                class="w-50 h-50 object-contain"
              />
            </div>
            <div class="flex items-center gap-3">
              <img
                src="https://logos-world.net/wp-content/uploads/2022/11/Pirelli-Logo-500x281.png"
                alt="Goodyear logo"
                class="w-50 h-50 object-contain"
              />
            </div>
          </div>
        </div>

        <div class="bg-gray-100 relative overflow-hidden rounded-xl p-10 md:p-16">
          <div class="flex flex-col gap-4 text-center md:text-left">
            <h2 class="text-4xl font-semibold">Eredményeink számokban</h2>
            <p class="max-w-xl">
              Több ezer elégedett vásárló, gyors kiszállítás és széles választék tesz minket
              megbízható partnerré az autógumi piacon.
            </p>
          </div>
          <div class="mt-10 flex flex-wrap justify-between gap-10 text-center">
            <div class="flex flex-col gap-4">
              <p>Kiszolgált ügyfelek</p>
              <span class="text-4xl font-semibold md:text-5xl">10.000+</span>
            </div>
            <div class="flex flex-col gap-4">
              <p>Eladott gumiabroncsok</p>
              <span class="text-4xl font-semibold md:text-5xl">50.000+</span>
            </div>
            <div class="flex flex-col gap-4">
              <p>Vásárlói elégedettség</p>
              <span class="text-4xl font-semibold md:text-5xl">98%</span>
            </div>
            <div class="flex flex-col gap-4">
              <p>Átlagos kiszállítási idő</p>
              <span class="text-4xl font-semibold md:text-5xl">24 óra</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
