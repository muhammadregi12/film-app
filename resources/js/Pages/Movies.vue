<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">
      <!-- Header with animated title -->
      <div class="mb-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-2 bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-purple-600 animate-fade-in">
          Daftar Film Populer IMbd
        </h1>
        <p class="text-gray-600 animate-fade-in delay-100">Temukan film terbaik untuk ditonton</p>
      </div>

      <!-- Search and Filter Section -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-8 animate-fade-in-up">
        <!-- Search Input -->
        <div class="relative mb-6">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input
            v-model="search"
            @keydown.enter="fetchMovies"
            type="text"
            placeholder="Cari film berdasarkan judul..."
            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
          />
        </div>

        <!-- Genre Filter with scroll -->
        <div class="mb-2">
          <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Filter Genre</h3>
          <div class="relative">
            <div class="flex space-x-2 pb-2 overflow-x-auto scrollbar-hide">
              <button
                v-for="genre in genres"
                :key="genre.id"
                @click="filterByGenre(genre.id)"
                class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-all duration-200"
                :class="{
                  'bg-blue-600 text-white shadow-md': activeGenre === genre.id,
                  'bg-gray-100 text-gray-800 hover:bg-gray-200': activeGenre !== genre.id
                }"
              >
                {{ genre.name }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Movie Grid -->
      <div v-if="movies.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 animate-fade-in-up delay-100">
        <div
          v-for="movie in movies"
          :key="movie.id"
          @click="goToDetail(movie.id)"
          class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 cursor-pointer relative"
        >
          <!-- Movie Poster -->
          <div class="relative overflow-hidden">
            <img 
              :src="movie.poster_path ? 'https://image.tmdb.org/t/p/w500' + movie.poster_path : '/placeholder-movie.jpg'"
              :alt="movie.title"
              class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105"
            />
            <!-- Rating Badge -->
            <div class="absolute top-2 left-2 bg-yellow-400 text-gray-900 rounded-full px-2 py-1 text-xs font-bold flex items-center">
              <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              {{ movie.vote_average.toFixed(1) }}
            </div>
            <!-- Release Year -->
            <div class="absolute bottom-2 right-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
              {{ movie.release_date ? movie.release_date.substring(0, 4) : 'N/A' }}
            </div>
          </div>

          <!-- Movie Info -->
          <div class="p-4">
            <h2 class="font-bold text-lg mb-1 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ movie.title }}</h2>
            <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ movie.overview || 'Deskripsi tidak tersedia' }}</p>
            
            <!-- Genre Tags -->
            <div class="flex flex-wrap gap-1 mt-2">
              <span 
                v-for="genreId in movie.genre_ids.slice(0, 2)" 
                :key="genreId"
                class="text-xs px-2 py-1 bg-gray-100 rounded-full"
              >
                {{ getGenreName(genreId) }}
              </span>
              <span v-if="movie.genre_ids.length > 2" class="text-xs px-2 py-1 bg-gray-100 rounded-full">
                +{{ movie.genre_ids.length - 2 }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12 animate-fade-in">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">Tidak ada film yang ditemukan</h3>
        <p class="mt-1 text-sm text-gray-500">Coba gunakan kata kunci atau filter yang berbeda.</p>
        <div class="mt-6">
          <button 
            @click="resetFilters"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Reset Filter
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  movies: Array,
  genres: Array,
  initialGenre: Number
})

const search = ref('')
const activeGenre = ref(props.initialGenre || null)

// Get genre name by ID
const getGenreName = (genreId) => {
  const genre = props.genres.find(g => g.id === genreId)
  return genre ? genre.name : 'Unknown'
}

// Search by title
const fetchMovies = () => {
  activeGenre.value = null
  router.get('/movies', { query: search.value }, {
    preserveState: true,
    replace: true
  })
}

// Filter by genre
const filterByGenre = (genreId) => {
  activeGenre.value = genreId
  search.value = ''
  router.get('/movies', { genre: genreId }, {
    preserveState: true,
    replace: true
  })
}

// Reset all filters
const resetFilters = () => {
  activeGenre.value = null
  search.value = ''
  router.get('/movies', {}, {
    preserveState: true,
    replace: true
  })
}

// Navigasi ke detail
const goToDetail = (id) => {
  router.get(`/movies/${id}`)
}
</script>

<style>
/* Animations */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes fadeInUp {
  from { 
    opacity: 0;
    transform: translateY(20px);
  }
  to { 
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out forwards;
}

.animate-fade-in-up {
  animation: fadeInUp 0.6s ease-out forwards;
}

.delay-100 {
  animation-delay: 0.1s;
}

/* Custom scrollbar */
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Line clamping */
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

</style>