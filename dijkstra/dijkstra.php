<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Algoritmo de Dijkstra</title>
</head>
<body>
    <h1>Algoritmo de Dijkstra</h1>

    <?php
    class Grafo {
        private $vertices;
        private $matrizAdjacencia;

        public function __construct($vertices) {
            $this->vertices = $vertices;
            $this->matrizAdjacencia = array_fill(0, $vertices, array_fill(0, $vertices, 0));
        }

        public function adicionarAresta($origem, $destino, $peso) {
            $this->matrizAdjacencia[$origem][$destino] = $peso;
            $this->matrizAdjacencia[$destino][$origem] = $peso;
        }

        public function getMatrizAdjacencia() {
            return $this->matrizAdjacencia;
        }

        public function getVertices() {
            return $this->vertices;
        }
    }

    class Dijkstra {
        public static function encontrarCaminhoMinimo(Grafo $grafo, $origem) {
            $matrizAdjacencia = $grafo->getMatrizAdjacencia();
            $vertices = $grafo->getVertices();
            $distancias = array_fill(0, $vertices, INF);
            $visitados = array_fill(0, $vertices, false);

            $distancias[$origem] = 0;

            for ($i = 0; $i < $vertices - 1; $i++) {
                $minDistancia = self::minDistancia($distancias, $visitados, $vertices);
                $visitados[$minDistancia] = true;

                for ($j = 0; $j < $vertices; $j++) {
                    if (!$visitados[$j] && $matrizAdjacencia[$minDistancia][$j] != 0 && $distancias[$minDistancia] != INF && 
                    $distancias[$minDistancia] + $matrizAdjacencia[$minDistancia][$j] < $distancias[$j]) {
                        $distancias[$j] = $distancias[$minDistancia] + $matrizAdjacencia[$minDistancia][$j];
                    }
                }
            }

            return $distancias;
        }

        private static function minDistancia($distancias, $visitados, $vertices) {
            $min = INF;
            $minIndex = -1;

            for ($i = 0; $i < $vertices; $i++) {
                if (!$visitados[$i] && $distancias[$i] <= $min) {
                    $min = $distancias[$i];
                    $minIndex = $i;
                }
            }

            return $minIndex;
        }
    }

 

    class Caminhos {
        public static function encontrarTodosCaminhos(Grafo $grafo, $origem, $destino) {
            $matrizAdjacencia = $grafo->getMatrizAdjacencia();
            $vertices = $grafo->getVertices();

            $caminhos = [];
            $caminhoAtual = [];
            self::encontrarCaminhosRecursivamente($origem, $destino, $matrizAdjacencia, $caminhoAtual, $caminhos);

            return $caminhos;
        }

        private static function encontrarCaminhosRecursivamente($verticeAtual, $destino, $matrizAdjacencia, $caminhoAtual, &$caminhos) {
            $caminhoAtual[] = $verticeAtual;

            if ($verticeAtual == $destino) {
                $caminhos[] = $caminhoAtual;
            } 
            else {
                for ($i = 0; $i < count($matrizAdjacencia[$verticeAtual]); $i++) {
                    if ($matrizAdjacencia[$verticeAtual][$i] != 0 && !in_array($i, $caminhoAtual)) {
                        self::encontrarCaminhosRecursivamente($i, $destino, $matrizAdjacencia, $caminhoAtual, $caminhos);
                    }
                }
            }

            array_pop($caminhoAtual);
        }
    }

    $grafo = new Grafo(6);

    $grafo->adicionarAresta(0, 1, 2);
    $grafo->adicionarAresta(0, 2, 4);
    $grafo->adicionarAresta(1, 2, 2);
    $grafo->adicionarAresta(1, 3, 4);
    $grafo->adicionarAresta(1, 4, 2);
    $grafo->adicionarAresta(2, 4, 3);
    $grafo->adicionarAresta(3, 5, 2);
    $grafo->adicionarAresta(4, 5, 2);

    $origem = 0;
    $destino = 5;

    $distancias = Dijkstra::encontrarCaminhoMinimo($grafo, $origem);
    $caminhos = Caminhos::encontrarTodosCaminhos($grafo, $origem, $destino);
    ?>

    <h2>Distâncias Mínimas</h2>
    <ul>
        <?php
        for ($i = 0; $i < count($distancias); $i++) {
            echo "<li>Distância mínima do vértice $origem para o vértice $i é: " . $distancias[$i] . "</li>";
        }
        ?>
    </ul>

    <h2>Todos os Caminhos Possíveis</h2>
    <ul>
        <?php
        foreach ($caminhos as $caminho) {
            echo "<li>" . implode(" -> ", $caminho) . "</li>";
        }
        ?>
    </ul>
</body>
</html>
