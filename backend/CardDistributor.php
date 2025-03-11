<?php
/**
 * CardDistributor Class
 */
class CardDistributor {
    //card suits
    //Spade = S, Heart = H, Diamond = D, Club = C
    private $suits = ['S', 'H', 'D', 'C'];
    
    //card values
    //Ace = A, 2 = 2, 3 = 3, 4 = 4, 5 = 5, 6 = 6, 7 = 7, 8 = 8, 9 = 9, 10 = X, Jack = J, Queen = Q, King = K
    private $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', 'X', 'J', 'Q', 'K'];
    
    //Empty current deck of cards
    private $deck = [];

    //Initializes a fresh deck of cards
    public function __construct() {
        $this->initializeDeck();
    }

    /**
     * Creates a fresh deck of 52 cards
     * Each card is formatted as 'SUIT-VALUE' (e.g., 'S-A' for Ace of Spades)
     */
    private function initializeDeck() {
        $this->deck = [];
        foreach ($this->suits as $suit) {
            foreach ($this->values as $value) {
                $this->deck[] = "$suit-$value";
            }
        }
    }

    /**
     * Shuffles the deck using multiple passes of Fisher-Yates algorithm
     * Also performs deck cutting for better randomization
     */
    private function shuffleDeck() {
        // Do multiple passes of shuffling for better randomization
        for ($pass = 0; $pass < 3; $pass++) {
            // Fisher-Yates shuffle
            $count = count($this->deck);
            for ($i = $count - 1; $i > 0; $i--) {
                try {
                    $j = random_int(0, $i);
                    // Swap cards
                    $temp = $this->deck[$i];
                    $this->deck[$i] = $this->deck[$j];
                    $this->deck[$j] = $temp;
                } catch (Exception $e) {
                    // Fallback to mt_rand if random_int fails
                    $j = mt_rand(0, $i);
                    $temp = $this->deck[$i];
                    $this->deck[$i] = $this->deck[$j];
                    $this->deck[$j] = $temp;
                }
            }

            // Cut the deck at a random point (like a real dealer would)
            try {
                $cutPoint = random_int(0, $count - 1);
            } catch (Exception $e) {
                $cutPoint = mt_rand(0, $count - 1);
            }
            
            // Perform the cut
            $this->deck = array_merge(
                array_slice($this->deck, $cutPoint),
                array_slice($this->deck, 0, $cutPoint)
            );
        }
    }

    /**
     * Distributes cards to the specified number of people
     * 
     * @param int $numberOfPeople Number of people to distribute cards to
     * @return array Array of strings, each string contains comma-separated cards for one person
     * @throws Exception if an error occurs during distribution
     */
    public function distributeCards(int $numberOfPeople): array {
        try {
            // Start with a fresh shuffle
            $this->shuffleDeck();

            // Calculate how many cards each person gets
            $cardsPerPerson = floor(52 / $numberOfPeople);
            $remainingCards = 52 % $numberOfPeople;
            
            // Initialize result array
            $result = array_fill(0, $numberOfPeople, '');
            
            // Distribute cards
            $cardIndex = 0;
            for ($person = 0; $person < $numberOfPeople; $person++) {
                $cards = [];
                
                // Give base number of cards
                for ($i = 0; $i < $cardsPerPerson; $i++) {
                    if ($cardIndex < count($this->deck)) {
                        $cards[] = $this->deck[$cardIndex++];
                    }
                }
                
                // Distribute remaining cards (if any)
                if ($remainingCards > 0 && $cardIndex < count($this->deck)) {
                    $cards[] = $this->deck[$cardIndex++];
                    $remainingCards--;
                }
                
                // Format output for this person
                $result[$person] = !empty($cards) ? implode(',', $cards) : '';
            }

            return $result;

        } catch (Exception $e) {
            // Log error (in a real app, we'd use proper logging)
            error_log("Error in card distribution: " . $e->getMessage());
            throw new Exception('Irregularity occurred');
        }
    }
}
