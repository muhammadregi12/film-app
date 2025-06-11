<template>
  <div class="min-h-screen bg-gradient-to-b from-gray-900 to-gray-800 text-gray-100">
    <!-- Back Button with Glass Morphism -->
    <div class="container mx-auto px-4 py-6">
      <button 
        @click="$inertia.visit('/')"
        class="flex items-center text-gray-300 hover:text-white transition-all duration-300 bg-gray-800 bg-opacity-50 backdrop-blur-sm px-4 py-2 rounded-lg border border-gray-700 hover:border-gray-600 hover:bg-opacity-70"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Daftar Film
      </button>
    </div>

    <!-- Movie Content -->
    <div class="container mx-auto px-4 pb-12">
      <div class="flex flex-col lg:flex-row gap-8 animate-fade-in">
        <!-- Movie Poster Column -->
        <div class="lg:w-1/3 flex-shrink-0 space-y-6">
          <!-- Poster with Glow Effect -->
          <div class="relative rounded-xl overflow-hidden shadow-2xl transform hover:scale-[1.02] transition-transform duration-500 group">
            <div class="absolute inset-0 bg-blue-500/10 blur-md group-hover:opacity-50 opacity-0 transition-opacity duration-500"></div>
            <img 
              :src="movie.poster_path ? 'https://image.tmdb.org/t/p/w780' + movie.poster_path : '/placeholder-movie.jpg'"
              :alt="movie.title"
              class="w-full h-auto object-cover relative z-10"
              loading="lazy"
            />
            <div v-if="movie.vote_average" class="absolute top-4 right-4 bg-gradient-to-br from-yellow-400 to-yellow-600 text-gray-900 rounded-full w-12 h-12 flex items-center justify-center font-bold text-sm shadow-lg z-20">
              {{ movie.vote_average.toFixed(1) }}
            </div>
          </div>

          <!-- Quick Facts with Glass Morphism -->
          <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 shadow-lg">
            <h3 class="text-lg font-semibold mb-4 pb-2 border-b border-gray-700/50 flex items-center">
              <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Info Singkat
            </h3>
            <ul class="space-y-3">
              <li v-if="movie.release_date" class="flex items-start group">
                <div class="p-1 bg-blue-500/10 rounded-lg mr-3 group-hover:bg-blue-500/20 transition-colors">
                  <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm text-gray-400">Tanggal Rilis</p>
                  <p class="font-medium">{{ formatDate(movie.release_date) }}</p>
                </div>
              </li>
              
              <li v-if="movie.runtime" class="flex items-start group">
                <div class="p-1 bg-purple-500/10 rounded-lg mr-3 group-hover:bg-purple-500/20 transition-colors">
                  <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm text-gray-400">Durasi</p>
                  <p class="font-medium">{{ formatRuntime(movie.runtime) }}</p>
                </div>
              </li>
              
              <li v-if="movie.genres?.length" class="flex items-start group">
                <div class="p-1 bg-green-500/10 rounded-lg mr-3 group-hover:bg-green-500/20 transition-colors">
                  <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm text-gray-400">Genre</p>
                  <p class="font-medium">
                    <span v-for="(genre, index) in movie.genres" :key="genre.id">
                      {{ genre.name }}<span v-if="index < movie.genres.length - 1">, </span>
                    </span>
                  </p>
                </div>
              </li>
              
              <li v-if="movie.production_countries?.length" class="flex items-start group">
                <div class="p-1 bg-amber-500/10 rounded-lg mr-3 group-hover:bg-amber-500/20 transition-colors">
                  <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm text-gray-400">Negara</p>
                  <p class="font-medium">
                    <span v-for="(country, index) in movie.production_countries" :key="country.iso_3166_1">
                      {{ country.name }}<span v-if="index < movie.production_countries.length - 1">, </span>
                    </span>
                  </p>
                </div>
              </li>
              
              <li v-if="movie.spoken_languages?.length" class="flex items-start group">
                <div class="p-1 bg-red-500/10 rounded-lg mr-3 group-hover:bg-red-500/20 transition-colors">
                  <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm text-gray-400">Bahasa</p>
                  <p class="font-medium">
                    <span v-for="(language, index) in movie.spoken_languages" :key="language.iso_639_1">
                      {{ language.name }}<span v-if="index < movie.spoken_languages.length - 1">, </span>
                    </span>
                  </p>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <!-- Movie Details Column -->
        <div class="lg:w-2/3 space-y-8">
          <!-- Title and Tagline -->
          <div class="mb-6">
            <h1 class="text-4xl md:text-5xl font-bold mb-2 bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
              {{ movie.title }}
              <span v-if="movie.release_date" class="text-gray-400 text-2xl font-normal">
                ({{ new Date(movie.release_date).getFullYear() }})
              </span>
            </h1>
            <p v-if="movie.tagline" class="text-xl text-gray-300 italic opacity-80">"{{ movie.tagline }}"</p>
            
            <!-- Status and Popularity Badges -->
            <div class="flex flex-wrap gap-2 mt-4">
              <span v-if="movie.status" class="px-3 py-1 bg-gray-800 rounded-full text-sm border border-gray-700">
                {{ movie.status }}
              </span>
              <span v-if="movie.popularity" class="px-3 py-1 bg-gray-800 rounded-full text-sm border border-gray-700 flex items-center">
                <svg class="w-4 h-4 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                {{ Math.round(movie.popularity) }} Popularity
              </span>
              <span v-if="movie.budget && movie.budget > 0" class="px-3 py-1 bg-gray-800 rounded-full text-sm border border-gray-700">
                Budget: ${{ movie.budget.toLocaleString() }}
              </span>
              <span v-if="movie.revenue && movie.revenue > 0" class="px-3 py-1 bg-gray-800 rounded-full text-sm border border-gray-700">
                Revenue: ${{ movie.revenue.toLocaleString() }}
              </span>
            </div>
          </div>

          <!-- Overview with Read More -->
          <div class="mb-8 bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 shadow-lg">
            <h2 class="text-2xl font-semibold mb-4 pb-2 border-b border-gray-700/50 flex items-center">
              <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
              Sinopsis
            </h2>
            <p class="text-gray-300 leading-relaxed">
              {{ movie.overview || 'Tidak ada sinopsis tersedia.' }}
            </p>
          </div>

          <!-- Additional Sections Grid -->
          <div class="grid md:grid-cols-2 gap-6">
            <!-- Production Companies -->
            <div v-if="movie.production_companies?.length" class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 shadow-lg">
              <h3 class="text-xl font-semibold mb-4 pb-2 border-b border-gray-700/50 flex items-center">
                <svg class="w-5 h-5 text-purple-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Produksi
              </h3>
              <div class="flex flex-wrap gap-4">
                <div v-for="company in movie.production_companies" :key="company.id" class="flex items-center group">
                  <div class="bg-gray-700/50 p-2 rounded-lg group-hover:bg-gray-700 transition-colors">
                    <img 
                      v-if="company.logo_path"
                      :src="'https://image.tmdb.org/t/p/w200' + company.logo_path" 
                      :alt="company.name"
                      class="h-8 max-w-[120px] object-contain filter grayscale group-hover:grayscale-0 transition-all"
                      loading="lazy"
                    >
                    <span v-else class="text-gray-300 text-sm">{{ company.name }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Cast -->
            <div v-if="credits?.cast?.length" class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 shadow-lg">
              <h3 class="text-xl font-semibold mb-4 pb-2 border-b border-gray-700/50 flex items-center">
                <svg class="w-5 h-5 text-amber-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Pemeran Utama
              </h3>
              <div class="flex overflow-x-auto pb-4 -mx-2 px-2 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800 gap-4">
                <div v-for="person in credits.cast.slice(0, 8)" :key="person.id" class="flex-shrink-0 w-20">
                  <div class="flex flex-col items-center group">
                    <div class="relative mb-2">
                      <img 
                        :src="person.profile_path ? 'https://image.tmdb.org/t/p/w185' + person.profile_path : '/placeholder-person.jpg'"
                        :alt="person.name"
                        class="w-16 h-16 rounded-full object-cover border-2 border-gray-700 group-hover:border-blue-400 transition-all"
                        loading="lazy"
                      >
                      <div class="absolute inset-0 rounded-full bg-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="text-center">
                      <p class="text-sm font-medium text-gray-100 group-hover:text-white transition-colors truncate w-full">{{ person.name }}</p>
                      <p class="text-xs text-gray-500 group-hover:text-gray-400 transition-colors truncate w-full">{{ person.character }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Videos Section -->
          <div v-if="videos?.results?.filter(v => v.site === 'YouTube').length" class="mt-8 bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 shadow-lg">
            <h3 class="text-xl font-semibold mb-4 pb-2 border-b border-gray-700/50 flex items-center">
              <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
              </svg>
              Video
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div 
                v-for="video in videos.results.filter(v => v.site === 'YouTube').slice(0, 4)" 
                :key="video.id"
                class="relative aspect-video bg-gray-900 rounded-xl overflow-hidden border border-gray-700/50 hover:border-gray-600 transition-all group"
              >
                <img 
                  :src="'https://img.youtube.com/vi/' + video.key + '/hqdefault.jpg'"
                  :alt="video.name"
                  class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:opacity-90 transition-opacity"
                  loading="lazy"
                >
                <div class="absolute inset-0 flex items-center justify-center">
                  <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center transform group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                    </svg>
                  </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                  <p class="text-white font-medium">{{ video.name }}</p>
                  <p class="text-gray-300 text-sm">{{ video.type }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  movie: Object,
  credits: Object,
  videos: Object
})

const formatDate = (dateString) => {
  if (!dateString) return 'Tidak diketahui'
  const options = { year: 'numeric', month: 'long', day: 'numeric' }
  return new Date(dateString).toLocaleDateString('id-ID', options)
}

const formatRuntime = (minutes) => {
  if (!minutes) return 'Tidak diketahui'
  const hours = Math.floor(minutes / 60)
  const mins = minutes % 60
  return `${hours}j ${mins}m`
}
</script>

<style>
/* Smooth animations */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeIn 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

/* Custom scrollbar */
.scrollbar-thin::-webkit-scrollbar {
  height: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
  background: rgba(31, 41, 55, 0.5);
  border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
  background: rgba(75, 85, 99, 0.8);
  border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: rgba(107, 114, 128, 0.8);
}

/* Hover effects */
.hover-glow:hover {
  box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
}

/* Glass morphism effect */
.glass {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}
</style>

